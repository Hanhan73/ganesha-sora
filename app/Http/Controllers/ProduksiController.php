<?php

namespace App\Http\Controllers;

use App\Models\Produksi;
use App\Models\StokBarang;
use App\Models\ProdukJadi;
use App\Models\BahanBaku;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ProduksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Produksi::with('produkJadi')->orderBy('id_produksi', 'asc')->paginate(10);
    
        if ($request->has('search') && !empty($request->search)) {
            $query->whereHas('produkJadi', function ($q) use ($request) {
                $q->where('produk', 'like', '%' . $request->search . '%');
            });
        }
    
        $data = $query->paginate(10)->withQueryString();
    
        return view('produksi.index', compact('data'));
    }

    public function create()
    {
    return view('produksi.create', [
        'produkJadi' => ProdukJadi::all(),
        'bahanBaku' => BahanBaku::all()
    ]);
    }

public function store(Request $request)
{

    $tanggalProduksi = Carbon::parse($request->tanggal)->format('Ymd');
    $countToday = Produksi::whereDate('tanggal', $request->tanggal)->count() + 1;
    $nomorUrut = str_pad($countToday, 4, '0', STR_PAD_LEFT);
    $kodeProduksi = 'PRD-' . $tanggalProduksi . '-' . $nomorUrut;
    $produksi = Produksi::create([
        'id_produksi' => $kodeProduksi,
        'tanggal' => $request->tanggal,
        'produk_id' => $request->produk_id,
        'user_id' => auth()->id(),
        'jumlah_produksi' => $request->jumlah_produksi,
    ]);


    $bahanList = $request->bahan_baku;
    $jumlahList = $request->jumlah_bahan;

    foreach ($bahanList as $index => $id_bahan) {
        $jumlah = $jumlahList[$index];

        $produksi->bahanBaku()->attach($id_bahan, ['jumlah_bahan_baku' => $jumlah]);
        $bahan = BahanBaku::where('id', $id_bahan)->first();
        if ($bahan) {
            $stokBahan = StokBarang::where('id_barang', $bahan->id_bahan_baku)->first();
            $stokBahan->stok -= $jumlah;
            $stokBahan->save();
        }
    }


    $produk = ProdukJadi::where('id', $request->produk_id)->first();
    if ($produk) {
        $stokProduk = StokBarang::where('id_barang', $produk->id_produk_jadi)->first();
        $stokProduk->stok += $request->jumlah_produksi;
        $stokProduk->save();
    }

    return redirect()->route('produksi.index')->with('success', 'Produksi berhasil ditambahkan.');
}


    public function edit(Produksi $produksi)
    {
        $produkJadi = ProdukJadi::all();
        $bahanBaku = BahanBaku::all();
        $produksi->load('bahanBaku');

        return view('produksi.edit', compact('produksi', 'produkJadi', 'bahanBaku'));
    }


    public function update(Request $request, Produksi $produksi)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'produk_id' => 'required',
            'jumlah_produksi' => 'required|numeric|min:1',
            'bahan_baku' => 'required|array',
            'jumlah_bahan' => 'required|array',
        ]);
    
        foreach ($produksi->bahanBaku as $bahan) {
            $stok = StokBarang::where('id_barang', $bahan->id_bahan_baku)
                              ->where('jenis_barang', 'bahan_baku')
                              ->first();
            if ($stok) {
                $stok->stok += $bahan->pivot->jumlah_bahan_baku;
                $stok->save();
            }
        }
    
        $produkLama = ProdukJadi::find($produksi->produk_id);
        if ($produkLama) {
            $stokProduk = StokBarang::where('id_barang', $produkLama->id_produk_jadi)
                                    ->where('jenis_barang', 'produk_jadi')
                                    ->first();
            if ($stokProduk) {
                $stokProduk->stok -= $produksi->jumlah_produksi;
                $stokProduk->save();
            }
        }
    
        $produksi->update([
            'tanggal' => $request->tanggal,
            'produk_id' => $request->produk_id,
            'jumlah_produksi' => $request->jumlah_produksi,
        ]);
    
        $produksi->bahanBaku()->detach();
    
        foreach ($request->bahan_baku as $index => $id_bahan) {
            $jumlah = $request->jumlah_bahan[$index] ?? 0;
            $produksi->bahanBaku()->attach($id_bahan, ['jumlah_bahan_baku' => $jumlah]);
    
            $bahan = BahanBaku::find($id_bahan);
            if ($bahan) {
                $stok = StokBarang::firstOrNew([
                    'id_barang' => $bahan->id_bahan_baku,
                    'jenis_barang' => 'bahan_baku'
                ]);
                $stok->barang = $bahan->bahan_baku;
                $stok->stok -= $jumlah;
                $stok->save();
            }
        }
    
        $produkBaru = ProdukJadi::find($request->produk_id);
        if ($produkBaru) {
            $stokProduk = StokBarang::firstOrNew([
                'id_barang' => $produkBaru->id_produk_jadi,
                'jenis_barang' => 'produk_jadi'
            ]);
            $stokProduk->barang = $produkBaru->produk;
            $stokProduk->stok += $request->jumlah_produksi;
            $stokProduk->save();
        }
    
        return redirect()->route('produksi.index')->with('success', 'Data produksi berhasil diperbarui.');
    }
    

    public function destroy(Produksi $produksi)
    {
        $produksi->delete();
        return redirect()->route('produksi.index');
    }
}