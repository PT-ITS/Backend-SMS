<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengajuansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengajuans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pemohon');
            $table->string('alamat_pemohon');
            $table->string('jabatan_pemohon');
            $table->string('ktp');
            $table->string('kta');
            $table->date('tanggal_mulai');
            $table->date('tanggal_berakhir');
            $table->string('alamat_tujuan');
            $table->text('deskripsi_pengajuan');
            $table->enum('jenis_pengajuan', [
                '0', //liburan
                '1', //umrah
                '2', //pendidikan
                '3', //lain-lain
            ]);
            $table->enum('status_pengajuan', [
                '0', //proses
                '1', //disetujui
                '2', //ditolak
            ])->default('0');
            $table->foreignId('id_pemohon')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_atasan')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengajuans');
    }
}
