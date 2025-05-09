<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BahanBaku;
use App\Models\Pelanggan;
use App\Models\Supplier;
use App\Models\ProdukJadi;
use App\Models\StokBarang;
use App\Models\PermintaanBahanBaku;
use App\Models\PenjualanBarang;
use App\Models\Pembelian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{

    public function index()
    {
        $data = [];
        $user = Auth::user();
        $data['totalBahanBaku'] = 0;
        $data['totalProdukJadi'] = 0;
        $data['totalPelanggan'] = 0;
        $data['totalSupplier'] = 0;
        $data['totalPembelian'] = 0;
        $data['totalPenjualan'] = 0;
        $data['stokMinimum'] = 0;
        $data['stokMinimumList'] = [];
        $data['permintaanTerbaru'] = [];
        if ($user->hasRole('admin')) {

            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();

            $data['totalBahanBaku'] = BahanBaku::count();
            $data['totalProdukJadi'] = ProdukJadi::count();
            $data['totalPelanggan'] = Pelanggan::count();
            $data['totalSupplier'] = Supplier::count();

            $data['totalPenjualan'] = PenjualanBarang::whereBetween('created_at', [$startOfWeek, $endOfWeek])->sum('total');
            $data['totalPembelian'] = Pembelian::whereBetween('created_at', [$startOfWeek, $endOfWeek])->sum('total');
        }
    
        if ($user->hasRole('admin') || $user->hasRole('gudang')) {
            $data['stokMinimumList'] = StokBarang::orderBy('stok', 'asc')
                ->take(5)
                ->get();
        }
    
    
        if ($user->hasRole('admin') || $user->hasRole('direktur')) {
            $penjualanChart = [
                'labels' => [],
                'data' => []
            ];
    
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $penjualanChart['labels'][] = $date->format('D, d M');
                $penjualanChart['data'][] = PenjualanBarang::whereDate('tanggal', $date)
                    ->sum('total');
            }
            if ($user->hasRole('admin') || $user->hasRole('gudang') || $user->hasRole('produksi')) {
                $data['permintaanTerbaru'] = PermintaanBahanBaku::with(['bahanBaku'])
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
            }
    
            $data['penjualanChart'] = $penjualanChart;
        }
    
        return view('welcome', $data);
    }
    

    /**
     * Show detailed statistics for admin users
     *
     * @return \Illuminate\Http\Response
     */
    public function statistics()
    {
        // Check if user has admin role
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('direktur')) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $monthlyStats = [];
        $currentYear = now()->year;
        
        // Generate monthly sales and production data
        for ($month = 1; $month <= 12; $month++) {
            $startDate = "$currentYear-$month-01";
            $endDate = date('Y-m-t', strtotime($startDate));
            
            $monthlyStats[$month] = [
                'month' => date('F', mktime(0, 0, 0, $month, 1)),
                'sales' => PenjualanBarang::whereBetween('tanggal', [$startDate, $endDate])->sum('total'),
                'production' => ProdukJadi::whereBetween('created_at', [$startDate, $endDate])->count()
            ];
        }

        // Top selling products
        $topProducts = PenjualanBarang::select('produk_id')
            ->selectRaw('SUM(jumlah) as total_sold')
            ->whereYear('tanggal', $currentYear)
            ->groupBy('produk_id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->with('produk')
            ->get();

        return view('admin.statistics', [
            'monthlyStats' => $monthlyStats,
            'topProducts' => $topProducts
        ]);
    }

    /**
     * Show inventory status for gudang users
     *
     * @return \Illuminate\Http\Response
     */
    public function inventory()
    {
        // Check if user has appropriate role
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('gudang')) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $bahanBaku = StokBarang::where('jenis_barang', 'bahan_baku')
            ->with('bahanBaku')
            ->orderBy('stok', 'asc')
            ->get();
            
        $produkJadi = StokBarang::where('jenis_barang', 'produk_jadi')
            ->with('produkJadi')
            ->orderBy('stok', 'asc')
            ->get();

        return view('gudang.inventory', [
            'bahanBaku' => $bahanBaku,
            'produkJadi' => $produkJadi
        ]);
    }

    /**
     * Show production overview for produksi users
     *
     * @return \Illuminate\Http\Response
     */
    public function production()
    {
        // Check if user has appropriate role
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('produksi')) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $pendingRequests = PermintaanBahanBaku::where('status', 'pending')
            ->with(['bahanBaku', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        $recentProduction = ProdukJadi::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('produksi.overview', [
            'pendingRequests' => $pendingRequests,
            'recentProduction' => $recentProduction
        ]);
    }
}