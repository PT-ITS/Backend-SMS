<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Saldo;
use App\Models\Pengajuan;
use App\Models\Pelaporan;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'level' => '1',
            'status' => '1',
            'password' => Hash::make('12345'),
        ]);
        User::create([
            'name' => 'pangkalan',
            'email' => 'pangkalan@gmail.com',
            'level' => '0',
            'status' => '1',
            'password' => Hash::make('12345'),
        ]);
        Pengajuan::create([
            "jenis_pengajuan" => 'ABCD',
            "nama_pemohon" => 'Kolonel Bagus Untoro',
            "alamat_pemohon" => 'Sumenep',
            "jabatan_pemohon" => 'Kolonel Khusus',
            "ktp" => 'test ktp',
            "kta" => 'test kta',
            "tanggal_mulai" => '2024/07/01',
            "tanggal_berakhir" => '2024/08/01',
            "alamat_tujuan" => 'Jakarta',
            "deskrispi_pengajuan" => 'Dinas ke pangkalan jakarta',
            "id_pemohon" => '2',
            "id_atasan" => '1',
        ]);
        Pelaporan::create([
            "kejadian" => 'Pencurian',
            "waktu_kejadian" => '2024/07/20',
            "pelaku_kejadian" => 'Belum diketahui',
            "bukti_kejadian" => 'test bukti',
            "deskripsi_kejadian" => 'test deskripsi',
            "tempat_kejadian" => 'keramaian',
            "id_pelapor" => '2'
        ]);
        
    }
}
