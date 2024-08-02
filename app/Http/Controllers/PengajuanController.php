<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use Illuminate\Support\Facades\Storage;

class PengajuanController extends Controller
{
    public function listAllPengajuan()
    {
        $dataPengajuan = Pengajuan::all();

        return response()->json(['message' => 'success', 'data' => $dataPengajuan]);
    }

    public function listPengajuanProses()
    {
        $dataPengajuan = Pengajuan::where('status_pengajuan', '0')->get();

        if ($dataPengajuan->isNotEmpty()) {
            return response()->json(['message' => 'success', 'data' => $dataPengajuan]);
        }
        return response()->json(['message' => 'no data found'], 401);
    }

    public function listPengajuanSudahProses()
    {
        $dataPengajuan = Pengajuan::where('status_pengajuan', '1')
            ->orWhere('status_pengajuan', '2')
            ->get();

        if ($dataPengajuan->isNotEmpty()) {
            return response()->json(['message' => 'success', 'data' => $dataPengajuan]);
        }
        return response()->json(['message' => 'no data found'], 401);
    }

    public function listMyPengajuan()
    {
        $dataPengajuan = Pengajuan::where('id_pemohon', auth()->user()->id)->get();

        if ($dataPengajuan->isNotEmpty()) {
            return response()->json(['message' => 'success', 'data' => $dataPengajuan]);
        }
        return response()->json(['message' => 'no data found'], 401);
    }

    public function createPengajuan(Request $request)
    {
        $validateData = $request->validate([
            "jenis_pengajuan" => "required|string",
            "nama_pemohon" => "required|string",
            "alamat_pemohon" => "required|string",
            "jabatan_pemohon" => "required|string",
            "ktp" => "required|image",
            "kta" => "required|image",
            "tanggal_mulai" => "required|date",
            "tanggal_berakhir" => "required|date",
            "alamat_tujuan" => "required|string",
            "deskripsi_pengajuan" => "required|string",
        ]);

        $ktpPath = $request->file('ktp')->store('ktp', 'public');
        $ktaPath = $request->file('kta')->store('kta', 'public');

        Pengajuan::create([
            "jenis_pengajuan" => $validateData['jenis_pengajuan'],
            "nama_pemohon" => $validateData['nama_pemohon'],
            "alamat_pemohon" => $validateData['alamat_pemohon'],
            "jabatan_pemohon" => $validateData['jabatan_pemohon'],
            "ktp" => $ktpPath,
            "kta" => $ktaPath,
            "tanggal_mulai" => $validateData['tanggal_mulai'],
            "tanggal_berakhir" => $validateData['tanggal_berakhir'],
            "alamat_tujuan" => $validateData['alamat_tujuan'],
            "deskripsi_pengajuan" => $validateData['deskripsi_pengajuan'],
            "id_pemohon" => '2',
            "id_atasan" => '1',
        ]);

        return response()->json(['message' => 'success'], 201);
    }

    public function updatePengajuan(Request $request, $id)
    {
        $validateData = $request->validate([
            "jenis_pengajuan" => "required|string",
            "nama_pemohon" => "required|string",
            "alamat_pemohon" => "required|string",
            "jabatan_pemohon" => "required|string",
            "ktp" => "nullable|image",
            "kta" => "nullable|image",
            "tanggal_mulai" => "required|date",
            "tanggal_berakhir" => "required|date",
            "alamat_tujuan" => "required|string",
            "deskripsi_pengajuan" => "required|string",
        ]);

        $dataPengajuan = Pengajuan::find($id);

        if ($dataPengajuan) {
            if ($request->hasFile('ktp')) {
                Storage::disk('public')->delete($dataPengajuan->ktp);
                $ktpPath = $request->file('ktp')->store('ktp', 'public');
                $dataPengajuan->ktp = $ktpPath;
            }

            if ($request->hasFile('kta')) {
                Storage::disk('public')->delete($dataPengajuan->kta);
                $ktaPath = $request->file('kta')->store('kta', 'public');
                $dataPengajuan->kta = $ktaPath;
            }

            $dataPengajuan->jenis_pengajuan = $validateData['jenis_pengajuan'];
            $dataPengajuan->nama_pemohon = $validateData['nama_pemohon'];
            $dataPengajuan->alamat_pemohon = $validateData['alamat_pemohon'];
            $dataPengajuan->jabatan_pemohon = $validateData['jabatan_pemohon'];
            $dataPengajuan->tanggal_mulai = $validateData['tanggal_mulai'];
            $dataPengajuan->tanggal_berakhir = $validateData['tanggal_berakhir'];
            $dataPengajuan->alamat_tujuan = $validateData['alamat_tujuan'];
            $dataPengajuan->deskripsi_pengajuan = $validateData['deskripsi_pengajuan'];
            $dataPengajuan->save();

            return response()->json(['message' => 'success']);
        }

        return response()->json(['message' => 'no data found'], 401);
    }

    public function deletePengajuan($id)
    {
        $dataPengajuan = Pengajuan::find($id);

        if ($dataPengajuan) {
            Storage::disk('public')->delete($dataPengajuan->ktp);
            Storage::disk('public')->delete($dataPengajuan->kta);
            $dataPengajuan->delete();

            return response()->json(['message' => 'success']);
        }

        return response()->json(['message' => 'no data found'], 401);
    }

    public function actionPengajuan(Request $request, $id)
    {
        $validateData = $request->validate([
            'action' => 'required|string|in:setuju,tolak'
        ]);

        $dataPengajuan = Pengajuan::where('id', $id)
            ->where('id_atasan', auth()->user()->id)
            ->first();

        if ($dataPengajuan) {
            if ($validateData['action'] == 'setuju') {
                $dataPengajuan->status_pengajuan = '1';
                $dataPengajuan->save();
                return response()->json(['message' => 'success'], 200);
            } else if ($validateData['action'] == 'tolak') {
                $dataPengajuan->status_pengajuan = '2';
                $dataPengajuan->save();
                return response()->json(['message' => 'success'], 200);
            } else {
                return response()->json(['message' => 'anda tidak memiliki akses'], 401);
            }
        } else {
            return response()->json(['message' => 'data pengajuan tidak ditemukan atau anda tidak memiliki akses'], 404);
        }
    }
}
