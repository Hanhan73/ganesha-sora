<?php

namespace App\Http\Controllers;

use App\Models\Produksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanProduksiController extends Controller
{
    public function index()
    {
        return view('laporan.produksi.index');
    }

    public function generate(Request $request)
    {
        $rules = [
            'jenis_laporan' => 'required|in:harian,bulanan,tahunan',
        ];
        
        if ($request->jenis_laporan === 'tahunan') {
            $rules['tanggal'] = 'required|digits:4|integer|min:2000|max:' . now()->year;
        } else {
            $rules['tanggal'] = 'required|date';
        }
        
        $request->validate($rules);

        $jenisLaporan = $request->jenis_laporan;
        $tanggal = Carbon::parse($request->tanggal);
        $debug_info = [];
        if ($jenisLaporan === 'tahunan') {
            $tahun = (int) $request->tanggal;
            $tanggal = Carbon::createFromDate($tahun, 1, 1);
        } else {
            $tanggal = Carbon::parse($request->tanggal);
        }

        $query = Produksi::with(['produkJadi', 'bahanBaku'])
            ->select(
                'produksi.*',
                DB::raw('DATE(tanggal) as tanggal_only')
            );

        $reportCode = $this->generateReportCode($jenisLaporan, $tanggal);

        switch ($jenisLaporan) {
            case 'harian':
                $query->whereDate('tanggal', $tanggal);
                $title = "Laporan Produksi Harian - " . $tanggal->format('d F Y');
                break;
            
            case 'bulanan':
                $query->whereYear('tanggal', $tanggal->year)
                     ->whereMonth('tanggal', $tanggal->month);
                $title = "Laporan Produksi Bulanan - " . $tanggal->format('F Y');
                break;
                
            case 'tahunan':
                $query->whereYear('tanggal', $tanggal->year);
                $title = "Laporan Produksi Tahunan - " . $tanggal->year;
                break;
        }

        $data = $query->orderBy('tanggal')->paginate(10)->withQueryString();

        if ($request->has('download')) {
            return $this->downloadCSV($data, $title, $reportCode);
        }

        return view('laporan.produksi.hasil', compact('data', 'title', 'jenisLaporan', 'tanggal', 'reportCode'));
    }

    private function generateReportCode($jenisLaporan, $tanggal)
    {
        $prefix = 'LPH';
        
        switch ($jenisLaporan) {
            case 'harian':
                $datePart = $tanggal->format('Ymd');
                break;
            
            case 'bulanan':
                $datePart = $tanggal->format('Ym');
                break;
                
            case 'tahunan':
                $datePart = $tanggal->format('Y');
                break;
        }

        return $prefix . '-' . $datePart;
    }

    private function downloadCSV($data, $title, $reportCode)
    {
        $filename = $reportCode . '.csv';
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($data, $reportCode) {
            $file = fopen('php://output', 'w');
            

            fputcsv($file, ['Kode Laporan: ' . $reportCode]);
            fputcsv($file, []); 
            
            fputcsv($file, [
                'No',
                'ID Produksi', 
                'Tanggal', 
                'Produk Jadi', 
                'Jumlah Produksi', 
                'Bahan Baku', 
                'Jumlah Bahan Baku',
                'Operator'
            ]);

            $counter = 1;
            foreach ($data as $produksi) {
                foreach ($produksi->bahanBaku as $bahan) {
                    fputcsv($file, [
                        $counter++,
                        $produksi->id_produksi,
                        $produksi->tanggal,
                        $produksi->produkJadi->nama_produk,
                        $produksi->jumlah_produksi,
                        $bahan->bahan_baku,
                        $bahan->pivot->jumlah_bahan_baku,
                        $produksi->user->name ?? '-'
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}