<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\BahanBaku;
use App\Models\Supplier;
use App\Models\StokBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PembelianController extends Controller{
    public function index()
    {
        $data = Pembelian::with(['suppliers', 'bahanBaku'])->orderBy('id_pembelian', 'asc')->paginate(10);
    
        return view('pembelian.index', compact('data'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $bahanBakus = BahanBaku::all();
        return view('pembelian.create', compact('suppliers', 'bahanBakus'));
    }

    public function store(Request $request){
    $tanggal = $request->tanggal ?? now()->format('Y-m-d');
    $tglFormat = date('Ymd', strtotime($tanggal));

    $last = DB::table('pembelian')
        ->whereDate('tanggal', $tanggal)
        ->orderBy('id_pembelian', 'desc')
        ->first();

    $urutan = $last ? (int) str_replace('PB-' . $tglFormat . '-', '', $last->id_pembelian) + 1 : 1;
    $kode = 'PB-' . $tglFormat . '-' . str_pad($urutan, 4, '0', STR_PAD_LEFT);

    $requestData = $request->all();
    $requestData['id_pembelian'] = $kode;
    $requestData['user_id'] = Auth::id();

    $pembelian = Pembelian::create($requestData);

        if ($request->has('bahan_baku') && $request->has('jumlah_pembelian')) {
            foreach ($request->bahan_baku as $index => $bahanId) {
                $jumlah = $request->jumlah_pembelian[$index] ?? 0;
                $harga = $request->harga[$index] ?? 0;

                if ($jumlah > 0) {
                    $pembelian->bahanBaku()->attach($bahanId, [
                        'jumlah_pembelian' => $jumlah,
                        'harga' => $harga
                    ]);

                    if ($request->status_pembayaran == 'Lunas') {
                        $bahanBaku = BahanBaku::find($bahanId);
                        if ($bahanBaku) {
                            $stok = StokBarang::firstOrNew([
                                'id_barang' => $bahanBaku->id_bahan_baku,
                                'jenis_barang' => 'bahan_baku'
                            ]);
                            $stok->barang = $bahanBaku->bahan_baku;
                            $stok->stok += $jumlah;
                            $stok->save();
                        }
                    }
                }
            }
        }

        return redirect()->route('pembelian.index');
    }




    public function edit(Pembelian $pembelian)
    {
            $suppliers = Supplier::all();
            $bahanBakus = BahanBaku::all();
            return view('pembelian.edit', compact('pembelian', 'suppliers', 'bahanBakus'));
    }

    public function update(Request $request, Pembelian $pembelian)
{
    $oldStatus = $pembelian->status_pembayaran;

    $pembelian->update($request->all());

    $bahanBakuData = [];
    if ($request->has('bahan_baku') && $request->has('jumlah_pembelian')) {
        foreach ($request->bahan_baku as $index => $bahanId) {
            $jumlah = $request->jumlah_pembelian[$index] ?? 0;
            $harga = $request->harga[$index] ?? 0;

            if ($jumlah > 0) {
                $bahanBakuData[$bahanId] = [
                    'jumlah_pembelian' => $jumlah,
                    'harga' => $harga
                ];
            }
        }
    }

    $pembelian->bahanBaku()->sync($bahanBakuData);

    if ($oldStatus !== 'Lunas' && $pembelian->status_pembayaran === 'Lunas') {
        foreach ($bahanBakuData as $bahanId => $data) {
            $bahanBaku = BahanBaku::find($bahanId);
            if ($bahanBaku) {
                $stok = StokBarang::firstOrNew([
                    'id_barang' => $bahanBaku->id_bahan_baku,
                    'jenis_barang' => 'bahan_baku'
                ]);
                $stok->barang = $bahanBaku->bahan_baku;
                $stok->stok += $data['jumlah_pembelian'];
                $stok->save();
            }
        }
    }

    return redirect()->route('pembelian.index');
}


    public function destroy(Pembelian $pembelian)
    {
        if ($pembelian->status_pembayaran === 'Lunas') {
            foreach ($pembelian->bahanBaku as $bahanBaku) {
                $jumlah = $bahanBaku->pivot->jumlah_pembelian;
                $stok = StokBarang::where('id_barang', $bahanBaku->id_bahan_baku)
                                ->where('jenis_barang', 'bahan_baku')
                                ->first();

                if ($stok) {
                    $stok->stok -= $jumlah;
                    $stok->save();
                }
            }
        }

        $pembelian->delete();
        return redirect()->route('pembelian.index');
    }
}