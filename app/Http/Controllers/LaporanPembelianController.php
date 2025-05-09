<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LaporanPembelianController extends Controller
{
    public function index()
    {
        return view('laporan.pembelian.index');
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
        if ($jenisLaporan === 'tahunan') {
            $tahun = (int) $request->tanggal;
            $tanggal = Carbon::createFromDate($tahun, 1, 1);
        } else {
            $tanggal = Carbon::parse($request->tanggal);
        }


        $query = Pembelian::with(['suppliers', 'bahanBaku'])
            ->select(
                'pembelian.*',
                DB::raw('DATE(tanggal) as tanggal_only')
            );

        $reportCode = $this->generateReportCode($jenisLaporan, $tanggal);


        switch ($jenisLaporan) {
            case 'harian':
                $query->whereDate('tanggal', $tanggal);
                $title = "Laporan Pembelian Harian - " . $tanggal->format('d F Y');
                break;
            
            case 'bulanan':
                $query->whereYear('tanggal', $tanggal->year)
                     ->whereMonth('tanggal', $tanggal->month);
                $title = "Laporan Pembelian Bulanan - " . $tanggal->format('F Y');
                break;
                
            case 'tahunan':
                $year = $tanggal->year;
                $debug_info['year_extracted'] = $year;
                
                $query->whereYear('tanggal', $year);
                
                $querySql = $query->toSql();
                $queryBindings = $query->getBindings();
                $debug_info['sql_query'] = $querySql;
                $debug_info['query_bindings'] = $queryBindings;
                
                $title = "Laporan Pembelian Tahunan - " . $year;
                break;
        }

        $data = $query->orderBy('tanggal')->paginate(10)->withQueryString();
        
        Log::info("Found {$data->count()} records for the {$jenisLaporan} report");
        $debug_info['record_count'] = $data->count();
        
        if ($data->count() > 0) {
            $debug_info['sample_data'] = [
                'first_record' => [
                    'id' => $data->first()->id_pembelian,
                    'tanggal' => $data->first()->tanggal,
                    'has_suppliers' => $data->first()->suppliers ? true : false,
                    'has_bahan_baku' => $data->first()->bahanBaku->count() > 0,
                ],
                'columns' => array_keys($data->first()->getAttributes()),
            ];
        }

        if ($request->has('download')) {
            return $this->downloadCSV($data, $title, $reportCode);
        }

        return view('laporan.pembelian.hasil', compact('data', 'title', 'jenisLaporan', 'tanggal', 'reportCode', 'debug_info'));
    }

    private function generateReportCode($jenisLaporan, $tanggal)
    {
        $prefix = 'LPB';
        
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

        $callback = function() use ($data, $reportCode, $title) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['Kode Laporan: ' . $reportCode]);
            fputcsv($file, [$title]);
            fputcsv($file, []);
            
            fputcsv($file, [
                'No',
                'ID Pembelian', 
                'Tanggal', 
                'Supplier', 
                'Bahan Baku', 
                'Jumlah', 
                'Subtotal', 
                'Status Pembayaran'
            ]);

            if ($data->isEmpty()) {
                fputcsv($file, ['Tidak ada data untuk periode ini']);
            } else {
                $counter = 1;
                $totalAmount = 0;
                
                foreach ($data as $pembelian) {
                    if ($pembelian->bahanBaku->isEmpty()) {
                        fputcsv($file, [
                            $counter++,
                            $pembelian->id_pembelian,
                            $pembelian->tanggal,
                            $pembelian->suppliers->supplier ?? '-',
                            '-',
                            0,
                            $pembelian->total,
                            $pembelian->status_pembayaran
                        ]);
                        
                        $totalAmount += $pembelian->total;
                    } else {
                        foreach ($pembelian->bahanBaku as $bahan) {
                            fputcsv($file, [
                                $counter++,
                                $pembelian->id_pembelian,
                                $pembelian->tanggal,
                                $pembelian->suppliers->supplier ?? '-',
                                $bahan->bahan_baku ?? '-',
                                $bahan->pivot->jumlah_pembelian ?? 0,
                                $pembelian->total,
                                $pembelian->status_pembayaran
                            ]);
                        }
                        
                        $totalAmount += $pembelian->total;
                    }
                }

                fputcsv($file, []);
                fputcsv($file, [
                    '', '', '', '', 'Total:', '',
                    $totalAmount,
                    ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}