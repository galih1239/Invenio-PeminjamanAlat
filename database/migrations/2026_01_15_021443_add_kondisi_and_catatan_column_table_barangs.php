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
        Schema::table(table:'barangs', callback: function (Blueprint $table):void{
            $table->enum('kondisi', ['baik', 'perbaikan', 'rusak'])
                ->default('baik')
                ->after('foto');
            $table->text('catatan')->nullable()->after('kondisi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
