<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use HasFactory;

    protected $fillable = [
        "jenis_pengajuan",
        "nama_pemohon",
        "alamat_pemohon",
        "jabatan_pemohon",
        "ktp",
        "kta",
        "tanggal_mulai",
        "tanggal_berakhir",
        "alamat_tujuan",
        "deskripsi_pengajuan",
        "status_pengajuan",
        "id_pemohon",
        "id_atasan"
    ];
}
