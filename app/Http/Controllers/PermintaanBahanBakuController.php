<?php

namespace App\Http\Controllers;

use App\Models\PermintaanBahanBaku;
use App\Models\User;
use App\Models\BahanBaku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PermintaanBahanBakuController extends Controller
{
    public function index()
    {
        $data = PermintaanBahanBaku::with(['user', 'bahanBaku'])->orderBy('id_permintaan', 'asc')->paginate(10);
        return view('permintaanbahanbaku.index', compact('data'));
    }        

    public function create()
    {
        $user = User::all();
        $bahanBakus = BahanBaku::all();
        return view('permintaanbahanbaku.create', compact('user', 'bahanBakus'));
    }

    public function store(Request $request)
{
    $tanggal = $request->tanggal ?? now()->format('Y-m-d');
    $tglFormat = date('Ymd', strtotime($tanggal));
    $last = PermintaanBahanBaku::orderBy('id', 'desc')->first();
    $urutan = $last ? $last->id + 1 : 1;
    $kode = 'PB-' . $tglFormat . '-' . str_pad($urutan, 4, '0', STR_PAD_LEFT);

    $permintaan = PermintaanBahanBaku::create([
        'id_permintaan' => $kode,
        'tanggal' => $tanggal,
        'user_id' => Auth::id(),
    ]);

    if (!empty($request->bahan_baku) && is_array($request->bahan_baku)) {
        foreach ($request->bahan_baku as $index => $bahanId) {
            $jumlah = $request->jumlah_permintaan[$index];
            $permintaan->bahanBaku()->attach($bahanId, ['jumlah_permintaan' => $jumlah]);
        }
    }

    return redirect()->route('permintaanbahanbaku.index')
                     ->with('success', 'Permintaan bahan baku berhasil disimpan');
}



    public function edit(PermintaanBahanBaku $permintaanbahanbaku)
    {
        $user = User::all();
        $bahanBakus = BahanBaku::all();
        return view('permintaanbahanbaku.edit', compact('permintaanbahanbaku', 'user', 'bahanBakus'));
    }

    public function update(Request $request, PermintaanBahanBaku $permintaanbahanbaku)
    {
        $permintaanbahanbaku->update([
            'tanggal' => $request->tanggal,
            'user_id' => Auth::id(), 
        ]);
    
        $permintaanbahanbaku->bahanBaku()->detach();
    
        if (!empty($request->bahan_baku) && is_array($request->bahan_baku)) {
            foreach ($request->bahan_baku as $index => $bahanId) {
                $jumlah = $request->jumlah_permintaan[$index];
                $permintaanbahanbaku->bahanBaku()->attach($bahanId, ['jumlah_permintaan' => $jumlah]);
            }
        }
    
        return redirect()->route('permintaanbahanbaku.index')->with('success', 'Permintaan berhasil diupdate');
    }

    public function destroy(PermintaanBahanBaku $permintaanbahanbaku)
    {
        $permintaanbahanbaku->delete();
        return redirect()->route('permintaanbahanbaku.index');
    }
}