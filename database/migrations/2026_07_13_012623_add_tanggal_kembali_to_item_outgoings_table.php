<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('item_outgoings', function (Blueprint $table) {
            $table->date('tanggal_kembali')->nullable()->after('tanggal_keluar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_outgoings', function (Blueprint $table) {
            $table->dropColumn('tanggal_kembali');
        });
    }
};
