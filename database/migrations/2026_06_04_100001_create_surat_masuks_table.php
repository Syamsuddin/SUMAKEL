<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_masuks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opd_id')->constrained('opds')->cascadeOnDelete();
            $table->foreignId('klasifikasi_id')->constrained('klasifikasis');
            $table->unsignedBigInteger('surat_keluar_id')->nullable();
            $table->integer('nomor_agenda');
            $table->string('nomor_surat');
            $table->string('asal_surat');
            $table->date('tanggal_surat');
            $table->date('tanggal_terima');
            $table->string('perihal');
            $table->string('sifat')->default('biasa');
            $table->string('status')->default('baru');
            $table->timestamps();

            $table->index('opd_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_masuks');
    }
};
