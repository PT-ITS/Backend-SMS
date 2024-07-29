<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelaporan;
use Illuminate\Support\Facades\Storage;

class PelaporanController extends Controller
{
    public function listAllLaporan()
    {
        $dataPelaporan = Pelaporan::all();
        
        return response()->json(['message' => 'success', 'data' => $dataPelaporan]);
    }
    
    public function listMyLaporan()
    {
        $dataPelaporan = Pelaporan::where('id_pelapor', auth()->user()->id)->get();

        if ($dataPelaporan->isNotEmpty()) {
            return response()->json(['message' => 'success', 'data' => $dataPelaporan]);
        }
        return response()->json(['message' => 'no data found'], 401);
    }

    public function createLaporan(Request $request)
    {
        $validateData = $request->validate([
            "kejadian" => "required|string",
            "waktu_kejadian" => "required|date",
            "pelaku_kejadian" => "required|string",
            "bukti_kejadian" => "required|image",
            "deskripsi_kejadian" => "required|string",
            "tempat_kejadian" => "required|string",
        ]);

        $buktiKejadianPath = $request->file('bukti_kejadian')->store('bukti_kejadian', 'public');

        Pelaporan::create([
            "kejadian" => $validateData['kejadian'],
            "waktu_kejadian" => $validateData['waktu_kejadian'],
            "pelaku_kejadian" => $validateData['pelaku_kejadian'],
            "bukti_kejadian" => $buktiKejadianPath,
            "deskripsi_kejadian" => $validateData['deskripsi_kejadian'],
            "tempat_kejadian" => $validateData['tempat_kejadian'],
        ]);

        return response()->json(['message' => 'success'], 201);
    }

    public function updateLaporan(Request $request, $id)
    {
        $validateData = $request->validate([
            "kejadian" => "required|string",
            "waktu_kejadian" => "required|date",
            "pelaku_kejadian" => "required|string",
            "bukti_kejadian" => "nullable|image",
            "deskripsi_kejadian" => "required|string",
            "tempat_kejadian" => "required|string",
        ]);

        $dataPelaporan = Pelaporan::find($id);

        if ($dataPelaporan) {
            if ($request->hasFile('bukti_kejadian')) {
                Storage::disk('public')->delete($dataPelaporan->bukti_kejadian);
                $buktiKejadianPath = $request->file('bukti_kejadian')->store('bukti_kejadian', 'public');
                $dataPelaporan->bukti_kejadian = $buktiKejadianPath;
            }

            $dataPelaporan->kejadian = $validateData['kejadian'];
            $dataPelaporan->waktu_kejadian = $validateData['waktu_kejadian'];
            $dataPelaporan->pelaku_kejadian = $validateData['pelaku_kejadian'];
            $dataPelaporan->deskripsi_kejadian = $validateData['deskripsi_kejadian'];
            $dataPelaporan->tempat_kejadian = $validateData['tempat_kejadian'];
            $dataPelaporan->save();

            return response()->json(['message' => 'success'], 200);       
        }    
        return response()->json(['message' => 'no data found'], 401);            
    }

    public function deleteLaporan($id)
    {
        $dataPelaporan = Pelaporan::find($id);

        if ($dataPelaporan) {
            Storage::disk('public')->delete($dataPelaporan->bukti_kejadian);
            $dataPelaporan->delete();
            return response()->json(['message' => 'success'], 200);         
        }    
        return response()->json(['message' => 'no data found'], 401);
    }
}
