<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getAvailableYears()
    {
        $years = Pengajuan::selectRaw('YEAR(created_at) as year')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->filter(function ($year) {
                return !is_null($year);
            });

        return response()->json(['message' => 'success', 'data' => $years]);
    }


    public function listCardData()
    {
        $dataPengajuanProses = Pengajuan::where('status_pengajuan', '0')->count();
        $dataPengajuanApproved = Pengajuan::where('status_pengajuan', '1')->count();
        $dataPengajuanRejected = Pengajuan::where('status_pengajuan', '2')->count();

        $dataDashboard = [
            "dataPengajuanProses" => $dataPengajuanProses,
            "dataPengajuanApproved" => $dataPengajuanApproved,
            "dataPengajuanRejected" => $dataPengajuanRejected,
        ];
        return response()->json(['message' => 'success', 'data' => $dataDashboard]);
    }

    public function listLineChartData(Request $request)
    {
        $year = $request->input('year', date('Y'));

        $data = Pengajuan::selectRaw('MONTH(created_at) as month, status_pengajuan, COUNT(*) as count')
            ->whereYear('created_at', $year)
            ->groupBy('month', 'status_pengajuan')
            ->orderBy('month')
            ->get();

        return response()->json(['message' => 'success', 'data' => $data]);
    }

    public function listPieChartData(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $data = Pengajuan::selectRaw('status_pengajuan, COUNT(*) as count')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->groupBy('status_pengajuan')
            ->get();

        return response()->json(['message' => 'success', 'data' => $data]);
    }
}
