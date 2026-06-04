<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nomor_counters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opd_id')->constrained('opds')->cascadeOnDelete();
            $table->string('jenis');
            $table->integer('tahun');
            $table->integer('nilai')->default(0);
            $table->timestamps();

            $table->unique(['opd_id', 'jenis', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nomor_counters');
    }
};
