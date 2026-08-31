<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SstockBrg extends Model
{
    protected $table = 'sstock_brg';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
        'noinven',
        'nama',
        'merk',
        'snumber',
        'stock',
        'pengadaan',
        'lokasi',
        'kondisi',
        'keterangan',
    ];

    protected $casts = [
        'stock' => 'integer',
    ];

    public function getNoInventarisAttribute(): ?string
    {
        return $this->noinven;
    }

    public function getNamaBarangAttribute(): ?string
    {
        return $this->nama;
    }

    public function getSerialNumberAttribute(): ?string
    {
        return $this->snumber;
    }

    public function getJumlahAttribute(): int
    {
        return (int) $this->stock;
    }

    public function getNamaPengadaanAttribute(): ?string
    {
        return $this->pengadaan;
    }

    public function getTahunPengadaanAttribute()
    {
        return $this->lokasi;
    }

    public function getKondisiBarangAttribute(): string
    {
        $value = trim((string) $this->kondisi);

        return match (strtolower($value)) {
            'baik' => 'baik',
            'rusak biasa', 'rusak ringan', 'rusak_ringan' => 'rusak_ringan',
            'rusak berat', 'rusak_berat' => 'rusak_berat',
            'hilang' => 'hilang',
            default => strtolower(str_replace(' ', '_', $value)) ?: 'baik',
        };
    }
}
