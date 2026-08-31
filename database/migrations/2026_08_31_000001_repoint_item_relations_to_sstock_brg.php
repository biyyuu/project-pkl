<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->dropForeignIfExists('item_outgoings', 'item_outgoings_item_id_foreign');
        $this->dropForeignIfExists('item_histories', 'item_histories_item_id_foreign');
    }

    public function down(): void
    {
        Schema::table('item_outgoings', function (Blueprint $table) {
            $table->foreign('item_id')
                ->references('id')
                ->on('items')
                ->cascadeOnDelete();
        });

        Schema::table('item_histories', function (Blueprint $table) {
            $table->foreign('item_id')
                ->references('id')
                ->on('items')
                ->cascadeOnDelete();
        });
    }

    private function dropForeignIfExists(string $table, string $constraint): void
    {
        $exists = DB::table('information_schema.table_constraints')
            ->where('table_schema', DB::raw('database()'))
            ->where('table_name', $table)
            ->where('constraint_name', $constraint)
            ->exists();

        if ($exists) {
            Schema::table($table, function (Blueprint $blueprint) use ($constraint) {
                $blueprint->dropForeign($constraint);
            });
        }
    }
};
