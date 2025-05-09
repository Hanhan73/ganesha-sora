<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function index(Request $request)
    {

        try {
            $query = Supplier::query();
    
            if ($request->has('search') && !empty($request->search)) {
                $query->where('supplier', 'like', '%' . $request->search . '%');
            }
    
            $data = $query->orderBy('id_supplier', 'asc')->paginate(10)->withQueryString();
    
            return view('supplier.index', compact('data'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat data bahan baku: ' . $e->getMessage());
        }

    }

    public function create()
    {
        return view('supplier.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:20',
            'nama_bank' => 'required|string|max:255',
            'no_rekening' => 'required|string|max:20',
        ]);

        DB::beginTransaction();
        try {
            $last = Supplier::orderBy('id_supplier', 'desc')->first();
            $urutan = $last ? (int) str_replace('SUP-', '', $last->id_supplier) + 1 : 1;
            
            if ($last && $last->id_supplier) {
                $lastKode = (int)substr($last->id_supplier, 4);
                $urutan = $lastKode + 1;
            }
            
            $kode = 'SUP-' . str_pad($urutan, 4, '0', STR_PAD_LEFT);
            
            $data = $request->all();
            $data['id_supplier'] = $kode;
            
            Supplier::create($data);
            
            DB::commit();
            return redirect()->route('supplier.index')->with('success', 'Supplier berhasil ditambahkan.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan supplier: ' . $e->getMessage());
        }
    }

    public function edit(Supplier $supplier)
    {
        return view('supplier.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'supplier' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:20',
            'nama_bank' => 'required|string|max:255',
            'no_rekening' => 'required|string|max:20',
        ]);

        try {
            $supplier->update($request->all());
            return redirect()->route('supplier.index')->with('success', 'Supplier berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui supplier: ' . $e->getMessage());
        }
    }

    public function destroy(Supplier $supplier)
    {
        DB::beginTransaction();
        try {
            $isUsed = DB::table('pembelian')->where('supplier_id', $supplier->id)->exists();
            
            if ($isUsed) {
                throw new \Exception('Supplier tidak dapat dihapus karena sudah digunakan dalam transaksi pembelian.');
            }
            
            $supplier->delete();
            DB::commit();
            return redirect()->route('supplier.index')->with('success', 'Supplier berhasil dihapus.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus supplier: ' . $e->getMessage());
        }
    }
}