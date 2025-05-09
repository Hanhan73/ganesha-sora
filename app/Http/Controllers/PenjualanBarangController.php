<?php

namespace App\Http\Controllers;

use App\Models\ProdukJadi;
use App\Models\Pelanggan;
use App\Models\StokBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\PenjualanBarang;

class PenjualanBarangController extends Controller
{
    public function index()
    {
        $data = PenjualanBarang::with(['pelanggan', 'produk'])->orderBy('id_penjualan', 'asc')->paginate(10);
        return view('penjualanbarang.index', compact('data'));
    }

    public function create()
    {
        $pelanggan = Pelanggan::all();
        $produk = ProdukJadi::all();
        return view('penjualanbarang.create', compact('pelanggan', 'produk'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'pelanggan_id' => 'required|exists:pelanggan,id',
            'produk_jadi' => 'required|array|min:1',
            'jumlah_penjualan.*' => 'required|integer|min:1',
            'harga.*' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'status_pembayaran' => 'required|in:Lunas,Belum Lunas',
        ]);

        $tanggal = $request->tanggal ?? now()->format('Y-m-d');
        $tglFormat = date('Ymd', strtotime($tanggal));
    
        $last = DB::table('penjualan_barang')
            ->whereDate('tanggal', $tanggal)
            ->orderBy('id_penjualan_barang', 'desc')
            ->first();
    
        $urutan = 1;
        if ($last) {
            $lastKode = explode('-', $last->id_penjualan_barang);
            $urutan = isset($lastKode[2]) ? (int) $lastKode[2] + 1 : 1;
        }
    
        $kode = 'PP-' . $tglFormat . '-' . str_pad($urutan, 4, '0', STR_PAD_LEFT);
    
        $requestData = $request->only(['tanggal', 'pelanggan_id', 'total', 'status_pembayaran']);
        $requestData['id_penjualan_barang'] = $kode;
        $requestData['user_id'] = Auth::id();
    
        $penjualan = PenjualanBarang::create($requestData);
    
        if ($request->has('produk_jadi') && $request->has('jumlah_penjualan')) {
            foreach ($request->produk_jadi as $index => $produkID) {
                $jumlah = $request->jumlah_penjualan[$index] ?? 0;
                $harga = $request->harga[$index] ?? 0;
    
                if ($jumlah > 0) {
                    $produkS = ProdukJadi::find($produkID);
                    $stokProduk = StokBarang::where('id_barang', $produkS->id_produk_jadi)
                                            ->where('jenis_barang', 'produk_jadi')
                                            ->first();
                    if ($stokProduk && $stokProduk->stok >= $jumlah) {
                        $stokProduk->stok -= $jumlah;
                        $stokProduk->save();
    
                        $penjualan->produk()->attach($produkID, [
                            'jumlah_penjualan' => $jumlah,
                            'harga' => $harga,
                        ]);
                    } else {
                        return redirect()->back()->with('error', 'Stok produk tidak mencukupi untuk produk ID: ' . $produkID);
                    }
                }
            }
        }
    
        return redirect()->route('penjualan.index')->with('success', 'Penjualan berhasil disimpan.');
    }
    

    public function edit(PenjualanBarang $penjualan)
    {
        $produk = ProdukJadi::all();
        $pelanggan = Pelanggan::all();
        $penjualan->load('produk');
    
        return view('penjualanbarang.edit', compact('penjualan', 'produk', 'pelanggan'));
    }
    
    public function update(Request $request, PenjualanBarang $penjualan)
    {
        // Validate the incoming request
        $request->validate([
            'tanggal' => 'required|date',
            'pelanggan_id' => 'required|exists:pelanggan,id',
            'produk_jadi' => 'required|array|min:1',
            'jumlah_penjualan.*' => 'required|integer|min:1',
            'harga.*' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'status_pembayaran' => 'required|in:Lunas,Belum Lunas',
        ]);
    
        // Update the basic fields for penjualanbarang
        $penjualan->update([
            'tanggal' => $request->tanggal,
            'pelanggan_id' => $request->pelanggan_id,
            'total' => $request->total,
            'status_pembayaran' => $request->status_pembayaran,
        ]);
    
        // Check if produk_jadi and jumlah_penjualan are provided and update them
        if ($request->has('produk_jadi') && $request->has('jumlah_penjualan')) {
            // Detach existing products first to avoid duplicates
            $penjualan->produk()->detach();
    
            // Attach the updated products with new quantity and price
            foreach ($request->produk_jadi as $index => $produkID) {
                $jumlah = $request->jumlah_penjualan[$index] ?? 0;
                $harga = $request->harga[$index] ?? 0;
    
                if ($jumlah > 0) {
                    $produkS = ProdukJadi::find($produkID);
                    $stokProduk = StokBarang::where('id_barang', $produkS->id_produk_jadi)
                                            ->where('jenis_barang', 'produk_jadi')
                                            ->first();
    
                    if ($stokProduk && $stokProduk->stok >= $jumlah) {
                        // Update stock after sale
                        $stokProduk->stok -= $jumlah;
                        $stokProduk->save();
    
                        // Attach the product to the sale with the quantity and price
                        $penjualan->produk()->attach($produkID, [
                            'jumlah_penjualan' => $jumlah,
                            'harga' => $harga,
                        ]);
                    } else {
                        return redirect()->back()->with('error', 'Stok produk tidak mencukupi untuk produk ID: ' . $produkID);
                    }
                }
            }
        }
    
        return redirect()->route('penjualan.index')->with('success', 'Penjualan berhasil diperbarui.');
    }
    

    public function destroy(PenjualanBarang $penjualan)
    {
        $penjualan->delete();
        return redirect()->route('penjualan.index')->with('success', 'Penjualan berhasil dihapus.');
    }
}