@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h5 class="m-0 font-weight-bold text-primary">Data Permintaan Bahan Baku</h5>
        <a href="{{ route('permintaanbahanbaku.create') }}" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="bi bi-plus-lg"></i>
            </span>
            <span class="text">Tambah Permintaan</span>
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th width="15%">ID Permintaan</th>
                        <th width="10%">Tanggal</th>
                        <th>User</th>
                        <th width="20%">Bahan Baku</th>
                        <th width="15%">Jumlah</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $permintaan)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                {{ $permintaan->id_permintaan }}
                            </span>
                        </td>
                        <td>{{ date('d M Y', strtotime($permintaan->tanggal)) }}</td>
                        <td>{{ $permintaan->user->username ?? '-' }}</td>
                        <td>
                            @foreach($permintaan->bahanBaku as $bahan)
                                <span class="badge bg-info bg-opacity-10 text-info mb-1 d-block">
                                    {{ $bahan->bahan_baku }}
                                </span>
                            @endforeach
                        </td>
                        <td>
                            @foreach($permintaan->bahanBaku as $bahan)
                                <span class="badge bg-secondary bg-opacity-10 text-secondary mb-1 d-block">
                                    {{ $bahan->pivot->jumlah_permintaan }}
                                </span>
                            @endforeach
                        </td>
                        <td class="text-center">
                            <a href="{{ route('permintaanbahanbaku.edit', $permintaan->id) }}" 
                               class="btn btn-sm btn-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('permintaanbahanbaku.destroy', $permintaan->id) }}" 
                                  method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Yakin ingin menghapus?')" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                    <td colspan="7" class="text-center py-4">
                            <div class="d-flex flex-column align-items-center">
                                <i class="bi bi-receipt fs-1 text-muted mb-2"></i>
                                <span class="text-muted">Tidak ada data permintaan bahan baku</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-3">
                {{ $data->links() }}
            </div>
        </div>
    </div>
</div>
@endsection