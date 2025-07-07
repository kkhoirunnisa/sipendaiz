@extends('layouts.app')

@section('content')
<style>
    .nav-tabs .nav-link.active {
    background:  linear-gradient(135deg, #ffd700, #ffb300) !important;
    color: #212529 !important; /* teks tetap gelap */
    font-weight: bold;
    border-color:rgb(255, 255, 255) !important;
}

</style>

<div class="px-3">
    <div class="mb-4" style="margin-left: 16px;">
        <div class="d-flex align-items-center mb-2">
            <div class="me-3">
                <div class="d-flex align-items-center justify-content-center bg-success bg-gradient text-white rounded-circle shadow-sm" style="width: 60px; height: 60px;">
                    <i class="fas fa-users fs-4"></i>
                </div>
            </div>
            <div>
                <h3 class="mb-0 fw-bold text-dark">Manajemen Pengurus Masjid</h3>
                <div class="d-flex align-items-center d-none d-md-flex">
                    <span class="text-muted">Users</span>
                    <i class="bi bi-chevron-right mx-2 text-muted small"></i>
                    <span class="text-success fw-semibold">Pengurus</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="fas fa-users me-2"></i>
                Daftar Pengurus Masjid
            </h4>
            <a href="{{ route('pejabat.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle-fill me-2"></i>Tambah Pengurus
            </a>
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active text-dark" data-bs-toggle="tab" data-bs-target="#aktif">
                        <i class="fas fa-user-check me-2 text-dark"></i>Pengurus Aktif
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link text-dark" data-bs-toggle="tab" data-bs-target="#riwayat">
                        <i class="fas fa-history me-2 text-dark"></i>Riwayat
                    </button>
                </li>
            </ul>

            <div class="tab-content mt-3 ">
                <div class="tab-pane fade show active " id="aktif">
                    <div class="row">
                        @foreach(['ketua_takmir', 'bendahara_takmir', 'ketua_pembangunan', 'bendahara_pembangunan'] as $jabatan)
                        @php
                        $pejabatAktif = $pejabat->where('jabatan', $jabatan)->where('aktif', true)->first();
                        @endphp
                        <div class="col-md-6 mb-4">
                            <div class="card border-secondary">
                                <div class="card-header text-dark table-gradient">
                                    <h5 class="mb-0">
                                        <i class="fas fa-user me-2  text-dark"></i>
                                        {{ ucwords(str_replace('_', ' ', $jabatan)) }}
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if($pejabatAktif)
                                    <div class="d-flex align-items-center mb-3">
                                        @if($pejabatAktif->foto_ttd)
                                        <img src="{{ asset('storage/' . $pejabatAktif->foto_ttd) }}" alt="Tanda Tangan" class="img-thumbnail me-3" style="width: 100px; height: 60px; object-fit: contain;">
                                        @else
                                        <div class="bg-light border rounded me-3 d-flex align-items-center justify-content-center" style="width: 100px; height: 60px;">
                                            <small class="text-muted">Tidak ada TTD</small>
                                        </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-1">{{ $pejabatAktif->nama }}</h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">Mulai Jabatan:</small>
                                            <div class="fw-bold">{{ $pejabatAktif->tanggal_mulai->format('d/m/Y') }}</div>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Lama Menjabat:</small>
                                            <div class="fw-bold">{{ $pejabatAktif->tanggal_mulai->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('pejabat.edit', $pejabatAktif->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit me-1"></i>Edit
                                        </a>
                                        <a href="{{ route('pejabat.riwayat', $jabatan) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-history me-1"></i>Riwayat
                                        </a>
                                    </div>
                                    @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-user-slash text-muted" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-2">Belum ada {{ ucwords(str_replace('_', ' ', $jabatan)) }} yang aktif</p>
                                        <a href="{{ route('pejabat.create') }}?jabatan={{ $jabatan }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-plus me-1"></i>Tambah
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="tab-pane fade" id="riwayat">
                    <div class="table-responsive border shadow rounded-4 mt-3">
                        <table class="table table-bordered table-hover align-items-center mb-0" style="min-width: 800px;">
                            <thead class="table-gradient text-white">
                                <tr>
                                    <th class="text-center">Jabatan</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Periode</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pejabat as $p)
                                <tr>
                                    <td class="text-center">{{ ucwords(str_replace('_', ' ', $p->jabatan)) }}</td>
                                    <td class="text-center">{{ $p->nama }}</td>
                                    <td class="text-center">
                                        <small>
                                            {{ $p->tanggal_mulai->format('d/m/Y') }}
                                            @if($p->tanggal_selesai)
                                            - {{ $p->tanggal_selesai->format('d/m/Y') }}
                                            @else
                                            - Sekarang
                                            @endif
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        @if($p->aktif)
                                        <span class="badge bg-success">Aktif</span>
                                        @else
                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-inline-flex" role="group">
                                            <a href="{{ route('pejabat.edit', $p->id) }}" class="btn btn-sm btn-warning me-1">
                                                <i class="bi bi-pen-fill"></i>
                                            </a>
                                            @if($p->foto_ttd)
                                            <button type="button" class="btn btn-sm btn-info me-1" data-bs-toggle="modal" data-bs-target="#ttdModal{{ $p->id }}">
                                                <i class="bi bi-brush"></i>
                                            </button>
                                            @endif
                                            <form id="delete-form-{{ $p->id }}" action="{{ route('pejabat.destroy', $p->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger" onclick="konfirmasiHapus('{{ $p->id }}')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-2">Belum ada data pejabat</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($pejabat as $p)
@if($p->foto_ttd)
<div class="modal fade" id="ttdModal{{ $p->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered"> {{-- Tengah secara vertikal --}}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tanda Tangan - {{ $p->nama }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="{{ asset('storage/' . $p->foto_ttd) }}" alt="Tanda Tangan {{ $p->nama }}" class="img-fluid">
            </div>
        </div>
    </div>
</div>
@endif
@endforeach
<script>
    function konfirmasiHapus(id) {
        Swal.fire({
            title: "Hapus Pengurus",
            html: "Apakah anda yakin ingin menghapus pejabat ini? <b>Data yang dihapus tidak dapat dikembalikan.</b>",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, hapus",
            cancelButtonText: "Tidak, batalkan",
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById("delete-form-" + id).submit();
            }
        });
    }
</script>

<!-- Notifikasi Sukses -->
@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session("success") }}',
        showConfirmButton: true,
        confirmButtonText: 'OK',
        confirmButtonColor: '#2d7d32',
        timer: 4000,
        timerProgressBar: true,
    });
</script>
@endif
<!-- Notifikasi Error -->
@if (session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session("error") }}',
        showConfirmButton: true,
        confirmButtonText: 'OK',
        confirmButtonColor: '#2d7d32',
    });
</script>
@endif

@endsection