<?php

namespace App\Http\Controllers;

use App\Models\StokBarang;
use App\Models\BahanBaku;
use App\Models\ProdukJadi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokBarangController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = StokBarang::query();
    
            if ($request->filled('search')) {
                $query->where('barang', 'like', '%' . $request->search . '%');
            }
    
            if ($request->filled('filter_jenis')) {
                $query->where('jenis_barang', $request->filter_jenis);
            }
    
            $data = $query->orderBy('id_barang', 'asc')->paginate(10)->withQueryString();
    
            return view('stokbarang.index', compact('data'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat data stok barang: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $bahanBakus = BahanBaku::all()->map(function ($item) {
                return [
                    'id_barang' => $item->id_bahan_baku,
                    'barang' => $item->bahan_baku,
                    'jenis_barang' => 'bahan_baku',
                ];
            });
        
            $produkJadi = ProdukJadi::all()->map(function ($item) {
                return [
                    'id_barang' => $item->id_produk_jadi,
                    'barang' => $item->produk,
                    'jenis_barang' => 'produk_jadi',
                ];
            });
        
            $items = $bahanBakus->merge($produkJadi);
        
            return view('stokbarang.create', compact('items'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat form tambah stok: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_barang' => 'required',
            'jenis_barang' => 'required|in:bahan_baku,produk_jadi',
            'barang' => 'required|string|max:255',
            'stok' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();
        try {
            $existingStock = StokBarang::where('id_barang', $request->id_barang)
                                     ->where('jenis_barang', $request->jenis_barang)
                                     ->first();
            
            if ($existingStock) {
                throw new \Exception('Stok untuk barang ini sudah ada. Gunakan edit untuk memperbarui.');
            }
            
            StokBarang::create($request->all());
            
            DB::commit();
            return redirect()->route('stokbarang.index')->with('success', 'Stok barang berhasil ditambahkan.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan stok barang: ' . $e->getMessage());
        }
    }

    public function edit(StokBarang $stokbarang)
    {
        try {
            $bahanBakus = BahanBaku::all()->map(function ($item) {
                return [
                    'id_barang' => $item->id_bahan_baku,
                    'barang' => $item->bahan_baku,
                    'jenis_barang' => 'bahan_baku',
                ];
            });
        
            $produkJadi = ProdukJadi::all()->map(function ($item) {
                return [
                    'id_barang' => $item->id_produk_jadi,
                    'barang' => $item->nama_produk,
                    'jenis_barang' => 'produk_jadi',
                ];
            });
        
            $items = $bahanBakus->merge($produkJadi);
        
            return view('stokbarang.edit', compact('items', 'stokbarang'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat form edit stok: ' . $e->getMessage());
        }
    }

    public function update(Request $request, StokBarang $stokbarang)
    {
        $request->validate([
            'stok' => 'required|numeric|min:0'
        ]);

        try {
            $stokbarang->update($request->only('stok'));
            return redirect()->route('stokbarang.index')->with('success', 'Stok barang berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui stok barang: ' . $e->getMessage());
        }
    }

    public function destroy(StokBarang $stokbarang)
    {
        DB::beginTransaction();
        try {
            $stokbarang->delete();
            DB::commit();
            return redirect()->route('stokbarang.index')->with('success', 'Stok barang berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus stok barang: ' . $e->getMessage());
        }
    }
}