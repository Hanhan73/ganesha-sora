<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BahanBakuController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = BahanBaku::query();
    
            if ($request->has('search') && !empty($request->search)) {
                $query->where('bahan_baku', 'like', '%' . $request->search . '%');
            }
    
            $data = $query->orderBy('id_bahan_baku', 'asc')->paginate(10)->withQueryString();
    
            return view('bahanbaku.index', compact('data'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat data bahan baku: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('bahanbaku.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'bahan_baku' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $last = BahanBaku::orderBy('id_bahan_baku', 'desc')->first();
            $urutan = $last ? (int) str_replace('BB-', '', $last->id_bahan_baku) + 1 : 1;
            
            if ($last && $last->id_bahan_baku) {
                $lastKode = (int)substr($last->id_bahan_baku, 4);
                $urutan = $lastKode + 1;
            }
            
            $kode = 'BB-' . str_pad($urutan, 4, '0', STR_PAD_LEFT);
            
            $data = $request->all();
            $data['id_bahan_baku'] = $kode;
            
            BahanBaku::create($data);
            
            DB::commit();
            return redirect()->route('bahanbaku.index')->with('success', 'Bahan baku berhasil ditambahkan.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan bahan baku: ' . $e->getMessage());
        }
    }

    public function edit(BahanBaku $bahanbaku)
    {
        return view('bahanbaku.edit', compact('bahanbaku'));
    }

    public function update(Request $request, BahanBaku $bahanbaku)
    {
        $request->validate([
            'bahan_baku' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
        ]);

        try {
            $bahanbaku->update($request->all());
            return redirect()->route('bahanbaku.index')->with('success', 'Bahan baku berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui bahan baku: ' . $e->getMessage());
        }
    }

    public function destroy(BahanBaku $bahanbaku)
    {
        DB::beginTransaction();
        try {
            // Check if bahan baku is used in any transaction
            $isUsed = DB::table('pembelian_bahan_baku')->where('bahan_baku_id', $bahanbaku->id)->exists() ||
                      DB::table('produksi_bahan_baku')->where('bahan_baku_id', $bahanbaku->id)->exists();
            
            if ($isUsed) {
                throw new \Exception('Bahan baku tidak dapat dihapus karena sudah digunakan dalam transaksi.');
            }
            
            $bahanbaku->delete();
            DB::commit();
            return redirect()->route('bahanbaku.index')->with('success', 'Bahan baku berhasil dihapus.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus bahan baku: ' . $e->getMessage());
        }
    }
}