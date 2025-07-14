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
        Schema::create('program_rows', function (Blueprint $table) {
            $table->id('row_id');
            $table->foreignId('program_id')->constrained('programs')->onDelete('cascade'); //fk program_id
            $table->text('inisiatif')->nullable();
            $table->text('peneraju_utama')->nullable();
            $table->string('tahun_mula_siap', 50)->nullable();
            $table->text('petunjuk_prestasi')->nullable();
            $table->text('pencapaian')->nullable();
            $table->string('status')->default('Belum Mula');
            $table->integer('completion')->default(0)->check('completion >= 0 and completion <= 100');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_rows');
    }
};
