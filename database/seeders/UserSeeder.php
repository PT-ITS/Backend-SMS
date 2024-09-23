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
        
        Pelaporan::create([
            "kejadian" => 'Pencurian',
            "waktu_kejadian" => '2024/07/20',
            "pelaku_kejadian" => 'Belum diketahui',
            "bukti_kejadian" => 'test bukti',
            "deskripsi_kejadian" => 'test deskripsi',
            "tempat_kejadian" => 'keramaian',
            "id_pelapor" => '2'
        ]);

        $pengajuanData = [
            [
                "jenis_pengajuan" => '0',
                "nama_pemohon" => 'Kolonel Bagus Untoro',
                "alamat_pemohon" => 'Sumenep',
                "jabatan_pemohon" => 'Kolonel Khusus',
                "ktp" => 'test ktp 1',
                "kta" => 'test kta 1',
                "tanggal_mulai" => '2024-07-01',
                "tanggal_berakhir" => '2024-08-01',
                "alamat_tujuan" => 'Jakarta',
                "deskrispi_pengajuan" => 'Dinas ke pangkalan jakarta',
                "id_pemohon" => 2,
                "id_atasan" => 1,
                "status_pengajuan" => "0"
            ],
            // Tambahkan lebih banyak data di sini
        ];

        // Mengisi data tambahan
        for ($i = 1; $i <= 20; $i++) {
            Pengajuan::create([
                "jenis_pengajuan" => (string) rand(0, 3), // random jenis_pengajuan
                "nama_pemohon" => "Pemohon $i",
                "alamat_pemohon" => "Alamat Pemohon $i",
                "jabatan_pemohon" => "Jabatan Pemohon $i",
                "ktp" => "ktp $i",
                "kta" => "kta $i",
                "tanggal_mulai" => '2024-07-' . str_pad($i, 2, '0', STR_PAD_LEFT),
                "tanggal_berakhir" => '2024-08-' . str_pad($i, 2, '0', STR_PAD_LEFT),
                "alamat_tujuan" => "Alamat Tujuan $i",
                "deskrispi_pengajuan" => "Deskripsi Pengajuan $i",
                "id_pemohon" => 2, // id_pemohon contoh
                "id_atasan" => 1,  // id_atasan contoh
                "status_pengajuan" => (string) rand(0, 3),
            ]);
        }
        
    }
}
