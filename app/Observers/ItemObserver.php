<?php

<?php

namespace App\Observers;

use App\Models\Item;

class ItemObserver
{
    public function created(Item $item): void
    {
        $item->histories()->create([
            'user_id' => auth()->id(),
            'action' => 'tambah',
            'jumlah_sesudah' => $item->jumlah,
        ]);
    }

    public function updated(Item $item): void
    {
        $item->histories()->create([
            'user_id' => auth()->id(),
            'action' => 'edit',
            'jumlah_sebelum' => $item->getOriginal('jumlah'),
            'jumlah_sesudah' => $item->jumlah,
        ]);
    }

    public function deleted(Item $item): void
    {
        $item->histories()->create([
            'user_id' => auth()->id(),
            'action' => 'hapus',
        ]);
    }
}

    /**
     * Handle the Item "restored" event.
     */
    public function restored(Item $item): void
    {
        //
    }

    /**
     * Handle the Item "force deleted" event.
     */
    public function forceDeleted(Item $item): void
    {
        //
    }
}
