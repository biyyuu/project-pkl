<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('no_inventaris')->unique();
            $table->string('nama_barang');
            $table->string('merk')->nullable();
            $table->string('serial_number')->nullable();
            $table->unsignedInteger('jumlah')->default(1);
            $table->string('nama_pengadaan')->nullable();
            $table->year('tahun_pengadaan')->nullable();
            $table->enum('kondisi_barang', ['baik', 'rusak_ringan', 'rusak_berat', 'hilang'])
                  ->default('baik');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};