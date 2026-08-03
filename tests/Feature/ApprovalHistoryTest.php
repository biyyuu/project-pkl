<?php

namespace Tests\Feature;

use App\Models\Borrower;
use App\Models\Item;
use App\Models\ItemHistory;
use App\Models\ItemOutgoing;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApprovalHistoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles and permissions
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_approve_outgoings_logs_to_history()
    {
        // Create an admin user
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // Create borrower
        $borrower = Borrower::create([
            'nama' => 'Peminjam A',
            'kontak' => '0812345',
        ]);

        // Create item
        $item = Item::create([
            'no_inventaris' => 'INV-001',
            'nama_barang' => 'Laptop ASUS',
            'merk' => 'ASUS',
            'jumlah' => 10,
            'kondisi_barang' => 'baik',
            'user_id' => $admin->id,
            'created_by' => $admin->id,
        ]);

        // Create outgoing item (stock already decremented at borrow time)
        $item->decrement('jumlah', 3);
        $outgoing = ItemOutgoing::create([
            'item_id' => $item->id,
            'borrower_id' => $borrower->id,
            'recorded_by' => $admin->id,
            'jumlah_keluar' => 3,
            'tanggal_keluar' => now(),
            'keperluan' => 'Rapat Kerja',
            'status' => 'pending',
        ]);

        // Act: approve the outgoing borrowing request
        $response = $this->actingAs($admin)
            ->post(route('approval.approve', $outgoing->id));

        $response->assertStatus(302);
        
        // Assertions:
        // 1. Status is updated
        $this->assertEquals('approved', $outgoing->fresh()->status);
        // 2. Stock remains at 7 (already decremented at borrow time)
        $this->assertEquals(7, $item->fresh()->jumlah);
        // 3. Item history is created
        $this->assertDatabaseHas('item_histories', [
            'item_id' => $item->id,
            'user_id' => $admin->id,
            'action' => 'keluar',
        ]);

        $latestHistory = ItemHistory::where('action', 'keluar')->orderByDesc('id')->first();
        $this->assertNotNull($latestHistory);
        $this->assertStringContainsString('Peminjaman disetujui oleh ' . $admin->name, $latestHistory->deskripsi);
        $this->assertStringContainsString('Peminjam A', $latestHistory->deskripsi);
        $this->assertStringContainsString('Rapat Kerja', $latestHistory->deskripsi);
    }

    public function test_reject_outgoings_restores_stock_and_logs_history()
    {
        // Create an admin user
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // Create borrower
        $borrower = Borrower::create([
            'nama' => 'Peminjam B',
            'kontak' => '08123456',
        ]);

        // Create item
        $item = Item::create([
            'no_inventaris' => 'INV-002',
            'nama_barang' => 'Projector Epson',
            'merk' => 'Epson',
            'jumlah' => 5,
            'kondisi_barang' => 'baik',
            'user_id' => $admin->id,
            'created_by' => $admin->id,
        ]);

        // Create outgoing item (stock already decremented at borrow time)
        $item->decrement('jumlah', 2);
        $outgoing = ItemOutgoing::create([
            'item_id' => $item->id,
            'borrower_id' => $borrower->id,
            'recorded_by' => $admin->id,
            'jumlah_keluar' => 2,
            'tanggal_keluar' => now(),
            'keperluan' => 'Presentasi Kerja',
            'status' => 'pending',
        ]);

        // Act: reject the outgoing borrowing request
        $response = $this->actingAs($admin)
            ->post(route('approval.reject', $outgoing->id));

        $response->assertStatus(302);

        // Assertions:
        // 1. Status is updated to rejected
        $this->assertEquals('rejected', $outgoing->fresh()->status);
        // 2. Stock is RESTORED (3 + 2 = 5 original)
        $this->assertEquals(5, $item->fresh()->jumlah);
        // 3. Item history is created
        $this->assertDatabaseHas('item_histories', [
            'item_id' => $item->id,
            'user_id' => $admin->id,
            'action' => 'ditolak',
            'jumlah_sebelum' => 3,
            'jumlah_sesudah' => 5,
        ]);

        $latestHistory = ItemHistory::where('action', 'ditolak')->orderByDesc('id')->first();
        $this->assertNotNull($latestHistory);
        $this->assertStringContainsString('Peminjaman ditolak oleh ' . $admin->name, $latestHistory->deskripsi);
        $this->assertStringContainsString('Peminjam B', $latestHistory->deskripsi);
        $this->assertStringContainsString('Presentasi Kerja', $latestHistory->deskripsi);
    }

    public function test_selesai_restores_stock_and_marks_completed()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $borrower = Borrower::create([
            'nama' => 'Peminjam C',
            'kontak' => '081234567',
        ]);

        $item = Item::create([
            'no_inventaris' => 'INV-003',
            'nama_barang' => 'Monitor LG',
            'merk' => 'LG',
            'jumlah' => 8,
            'kondisi_barang' => 'baik',
            'user_id' => $admin->id,
            'created_by' => $admin->id,
        ]);

        // Simulate: stock already decremented, item approved
        $item->decrement('jumlah', 2);
        $outgoing = ItemOutgoing::create([
            'item_id' => $item->id,
            'borrower_id' => $borrower->id,
            'recorded_by' => $admin->id,
            'jumlah_keluar' => 2,
            'tanggal_keluar' => now(),
            'keperluan' => 'Demo',
            'status' => 'approved',
        ]);

        // Act: complete the borrowing
        $response = $this->actingAs($admin)
            ->post(route('item-outgoing.selesai', $outgoing->id));

        $response->assertStatus(302);

        // Assertions:
        // 1. Status is completed
        $this->assertEquals('completed', $outgoing->fresh()->status);
        // 2. Stock is restored (6 + 2 = 8)
        $this->assertEquals(8, $item->fresh()->jumlah);
        // 3. History is logged
        $this->assertDatabaseHas('item_histories', [
            'item_id' => $item->id,
            'action' => 'selesai',
            'jumlah_sebelum' => 6,
            'jumlah_sesudah' => 8,
        ]);
    }

    public function test_history_filters_by_search_and_dates()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $itemA = Item::create([
            'no_inventaris' => 'INV-004',
            'nama_barang' => 'Kursi Kerja',
            'merk' => 'Informa',
            'jumlah' => 10,
            'kondisi_barang' => 'baik',
            'user_id' => $admin->id,
            'created_by' => $admin->id,
        ]);

        $itemB = Item::create([
            'no_inventaris' => 'INV-005',
            'nama_barang' => 'Meja Kerja',
            'merk' => 'Informa',
            'jumlah' => 5,
            'kondisi_barang' => 'baik',
            'user_id' => $admin->id,
            'created_by' => $admin->id,
        ]);

        // Override created_at of histories for itemA to 5 days ago for date filter testing
        ItemHistory::where('item_id', $itemA->id)->update(['created_at' => now()->subDays(5)]);
        ItemHistory::where('item_id', $itemB->id)->update(['created_at' => now()]);

        // 1. Text Search Filter: 'Kursi' — should only return histories for itemA
        $response = $this->actingAs($admin)
            ->get(route('history.index', ['search' => 'Kursi']));
        
        $response->assertStatus(200);
        $response->assertViewHas('histories');
        $histories = $response->viewData('histories');
        
        $itemIds = $histories->pluck('item_id')->unique()->values()->all();
        $this->assertContains($itemA->id, $itemIds);
        $this->assertNotContains($itemB->id, $itemIds);

        // 2. Date Filter: filter histories between 6 days ago and 2 days ago (Should only return itemA histories)
        $response = $this->actingAs($admin)
            ->get(route('history.index', [
                'start_date' => now()->subDays(6)->toDateString(),
                'end_date' => now()->subDays(2)->toDateString(),
            ]));
        
        $response->assertStatus(200);
        $histories = $response->viewData('histories');
        $itemIds = $histories->pluck('item_id')->unique()->values()->all();
        $this->assertContains($itemA->id, $itemIds);
        $this->assertNotContains($itemB->id, $itemIds);
    }
}
