<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelaporan extends Model
{
    use HasFactory;

    protected $fillable = [
        "kejadian",
        "waktu_kejadian",
        "pelaku_kejadian",
        "bukti_kejadian",
        "deskripsi_kejadian",
        "tempat_kejadian",
        "id_pelapor"
    ];
}
