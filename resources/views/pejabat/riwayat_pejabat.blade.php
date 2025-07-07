@extends('layouts.app')

@section('content')
<div class="px-3">
    <div class="mb-4" style="margin-left: 16px;">
        <div class="d-flex align-items-center mb-2">
            <div class="me-3">
                <div class="d-flex align-items-center justify-content-center bg-success bg-gradient text-white rounded-circle shadow-sm"
                    style="width: 60px; height: 60px;">
                    <i class="fas fa-history fs-4"></i>
                </div>
            </div>
            <div>
                <h3 class="mb-0 fw-bold text-dark">
                    Riwayat Pengurus - 
                    @switch($jabatan)
                        @case('ketua_takmir') Ketua Takmir @break
                        @case('bendahara_takmir') Bendahara Takmir @break
                        @case('ketua_pembangunan') Ketua Pembangunan @break
                        @case('bendahara_pembangunan') Bendahara Pembangunan @break
                        @default {{ ucwords(str_replace('_', ' ', $jabatan)) }}
                    @endswitch
                </h3>
                <div class="d-flex align-items-center d-none d-md-flex">
                    <span class="text-muted">Users</span>
                    <i class="bi bi-chevron-right mx-2 text-muted small"></i>
                    <a href="{{ route('pejabat.index') }}" class="text-muted text-decoration-none">Pengurus</a>
                    <i class="bi bi-chevron-right mx-2 text-muted small"></i>
                    <span class="text-success fw-semibold">Riwayat</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="fas fa-clock me-2"></i>
                Data Riwayat Pengurus
            </h4>
            <a href="{{ route('pejabat.index') }}" class="btn btn-success">
                <i class="bi bi-arrow-left-circle me-1"></i> Kembali
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive border shadow-sm rounded-4">
                <table class="table table-bordered table-hover align-middle mb-0" style="min-width: 800px;">
                    <thead class="table-gradient text-white">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama</th>
                            <th class="text-center">Tanggal Mulai</th>
                            <th class="text-center">Tanggal Selesai</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Tanda Tangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat as $i => $item)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td class="text-center">{{ $item->nama }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d-m-Y') }}</td>
                            <td class="text-center">
                                {{ $item->tanggal_selesai 
                                    ? \Carbon\Carbon::parse($item->tanggal_selesai)->format('d-m-Y')
                                    : '-' }}
                            </td>
                            <td class="text-center">
                                @if($item->aktif)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->foto_ttd)
                                    <img src="{{ asset('storage/' . $item->foto_ttd) }}" alt="TTD" style="height: 50px;">
                                @else
                                    <span class="text-muted">Tidak ada</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-2 mb-0">Belum ada riwayat pejabat.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
