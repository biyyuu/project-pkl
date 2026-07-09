<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'no_inventaris', 'nama_barang', 'merk', 'serial_number',
        'jumlah', 'nama_pengadaan', 'tahun_pengadaan',
        'kondisi_barang', 'keterangan', 'user_id', 'created_by',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function outgoings()
    {
        return $this->hasMany(ItemOutgoing::class);
    }

    public function histories()
    {
        return $this->hasMany(ItemHistory::class);
    }
}