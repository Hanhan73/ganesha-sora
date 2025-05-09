<?php

namespace App\Http\Controllers;

use App\Models\ProdukJadi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdukJadiController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = ProdukJadi::query();
    
            if ($request->has('search') && !empty($request->search)) {
                $query->where('produk', 'like', '%' . $request->search . '%');
            }
    
            $data = $query->orderBy('id_produk_jadi', 'asc')->paginate(10)->withQueryString();
    
            return view('produkjadi.index', compact('data'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat data bahan baku: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('produkjadi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $last = ProdukJadi::orderBy('id_produk_jadi', 'desc')->first();
            $urutan = $last ? (int) str_replace('PJ-', '', $last->id_produk_jadi) + 1 : 1;
            
            if ($last && $last->id_produk_jadi) {
                $lastKode = (int)substr($last->id_produk_jadi, 4);
                $urutan = $lastKode + 1;
            }
            
            $kode = 'PJ-' . str_pad($urutan, 4, '0', STR_PAD_LEFT);
            
            $data = $request->all();
            $data['id_produk_jadi'] = $kode;
            
            ProdukJadi::create($data);
            
            DB::commit();
            return redirect()->route('produkjadi.index')->with('success', 'Produk jadi berhasil ditambahkan.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan produk jadi: ' . $e->getMessage());
        }
    }

    public function edit(ProdukJadi $produkjadi)
    {
        return view('produkjadi.edit', compact('produkjadi'));
    }

    public function update(Request $request, ProdukJadi $produkjadi)
    {
        $request->validate([
            'produk' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
        ]);

        try {
            $produkjadi->update($request->all());
            return redirect()->route('produkjadi.index')->with('success', 'Produk jadi berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui produk jadi: ' . $e->getMessage());
        }
    }

    public function destroy(ProdukJadi $produkjadi)
    {
        DB::beginTransaction();
        try {
            $isUsed = DB::table('penjualan_produk')->where('produk_jadi_id', $produkjadi->id)->exists() ||
                      DB::table('produksi')->where('produk_id', $produkjadi->id)->exists();
            
            if ($isUsed) {
                throw new \Exception('Produk jadi tidak dapat dihapus karena sudah digunakan dalam transaksi.');
            }
            
            $produkjadi->delete();
            DB::commit();
            return redirect()->route('produkjadi.index')->with('success', 'Produk jadi berhasil dihapus.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus produk jadi: ' . $e->getMessage());
        }
    }
}