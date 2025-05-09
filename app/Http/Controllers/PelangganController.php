<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Pelanggan::query();
            if ($request->has('search') && !empty($request->search)) {
                $query->where('pelanggan', 'like', '%' . $request->search . '%');
            }
    
            $data = $query->orderBy('id_pelanggan', 'asc')->paginate(10)->withQueryString();
    
            return view('pelanggan.index', compact('data'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat data bahan baku: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('pelanggan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelanggan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:20',
        ]);

        DB::beginTransaction();
        try {
            $last = Pelanggan::orderBy('id_pelanggan', 'desc')->first();
            $urutan = $last ? (int) str_replace('PLG-', '', $last->id_pelanggan) + 1 : 1;
            
            if ($last && $last->id_pelanggan) {
                $lastKode = (int)substr($last->id_pelanggan, 4);
                $urutan = $lastKode + 1;
            }
            
            $kode = 'PLG-' . str_pad($urutan, 4, '0', STR_PAD_LEFT);
            
            $data = $request->all();
            $data['id_pelanggan'] = $kode;
            
            Pelanggan::create($data);
            
            DB::commit();
            return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan pelanggan: ' . $e->getMessage());
        }
    }

    public function edit(Pelanggan $pelanggan)
    {
        return view('pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $request->validate([
            'pelanggan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:20',
        ]);

        try {
            $pelanggan->update($request->all());
            return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui pelanggan: ' . $e->getMessage());
        }
    }

    public function destroy(Pelanggan $pelanggan)
    {
        DB::beginTransaction();
        try {
            // Check if pelanggan is used in any transaction
            $isUsed = DB::table('penjualan_barang')->where('pelanggan_id', $pelanggan->id)->exists();
            
            if ($isUsed) {
                throw new \Exception('Pelanggan tidak dapat dihapus karena sudah melakukan transaksi penjualan.');
            }
            
            $pelanggan->delete();
            DB::commit();
            return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil dihapus.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus pelanggan: ' . $e->getMessage());
        }
    }
}