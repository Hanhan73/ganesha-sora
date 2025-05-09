<?php

namespace App\Http\Controllers;

use App\Models\PenjualanBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanPenjualanController extends Controller
{
    public function index()
    {
        return view('laporan.penjualan.index');
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

        $query = PenjualanBarang::with(['pelanggan', 'produk'])
            ->select(
                'penjualan_barang.*',
                DB::raw('DATE(tanggal) as tanggal_only')
            );

        $reportCode = $this->generateReportCode($jenisLaporan, $tanggal);

        switch ($jenisLaporan) {
            case 'harian':
                $query->whereDate('tanggal', $tanggal);
                $title = "Laporan Penjualan Harian - " . $tanggal->format('d F Y');
                break;
            
            case 'bulanan':
                $query->whereYear('tanggal', $tanggal->year)
                     ->whereMonth('tanggal', $tanggal->month);
                $title = "Laporan Penjualan Bulanan - " . $tanggal->format('F Y');
                break;
                
            case 'tahunan':
                $query->whereYear('tanggal', $tanggal->year);
                $title = "Laporan Penjualan Tahunan - " . $tanggal->year;
                break;
        }

        $data = $query->orderBy('tanggal')->paginate(10)->withQueryString();

        if ($request->has('download')) {
            return $this->downloadCSV($data, $title, $reportCode);
        }

        return view('laporan.penjualan.hasil', compact('data', 'title', 'jenisLaporan', 'tanggal', 'reportCode'));
    }

    private function generateReportCode($jenisLaporan, $tanggal)
    {
        $prefix = 'LPJ';
        
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
            fputcsv($file, []); // Empty row
            
            fputcsv($file, [
                'No',
                'ID Penjualan', 
                'Tanggal', 
                'Pelanggan', 
                'Produk', 
                'Jumlah', 
                'Harga', 
                'Subtotal',
                'Status Pembayaran'
            ]);

            $counter = 1;
            foreach ($data as $penjualan) {
                foreach ($penjualan->produk as $produk) {
                    fputcsv($file, [
                        $counter++,
                        $penjualan->id_penjualan_barang,
                        $penjualan->tanggal,
                        $penjualan->pelanggan->nama_pelanggan ?? '-',
                        $produk->nama_produk,
                        $produk->pivot->jumlah_penjualan,
                        $produk->pivot->harga,
                        $produk->pivot->jumlah_penjualan * $produk->pivot->harga,
                        $penjualan->status_pembayaran
                    ]);
                }
            }

            fputcsv($file, []);
            fputcsv($file, [
                '', '', '', '', '', 'Total:',
                array_sum(array_map(function($p) { 
                    return $p->total; 
                }, $data->all()))
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}