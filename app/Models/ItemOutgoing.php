<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemOutgoing extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id', 'borrower_id', 'recorded_by',
        'jumlah_keluar', 'tanggal_keluar',
        'keperluan', 'keterangan',
    ];

    protected $casts = [
        'tanggal_keluar' => 'date',
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