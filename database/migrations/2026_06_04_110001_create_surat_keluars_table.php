<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_keluars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opd_id')->constrained('opds')->cascadeOnDelete();
            $table->foreignId('klasifikasi_id')->constrained('klasifikasis');
            $table->string('nomor')->nullable();
            $table->integer('nomor_urut')->nullable();
            $table->date('tanggal_surat');
            $table->string('jenis_tujuan')->default('eksternal');
            $table->string('tujuan_eksternal')->nullable();
            $table->foreignId('tujuan_opd_id')->nullable()->constrained('opds')->nullOnDelete();
            $table->string('perihal');
            $table->string('sifat')->default('biasa');
            $table->string('status')->default('draft');
            $table->timestamps();

            $table->index('opd_id');
        });

        Schema::table('surat_masuks', function (Blueprint $table) {
            $table->foreign('surat_keluar_id')->references('id')->on('surat_keluars')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('surat_masuks', function (Blueprint $table) {
            $table->dropForeign(['surat_keluar_id']);
        });

        Schema::dropIfExists('surat_keluars');
    }
};
