<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemOutgoing extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id', 'borrower_id', 'recorded_by',
        'jumlah_keluar', 'tanggal_keluar', 'tanggal_kembali',
        'keperluan', 'keterangan', 'status',
    ];

    protected $casts = [
        'tanggal_keluar' => 'date',
        'tanggal_kembali' => 'date',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function borrower()
    {
        return $this->belongsTo(Borrower::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}