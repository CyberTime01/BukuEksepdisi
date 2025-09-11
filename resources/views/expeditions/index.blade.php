@extends('layouts.app')

@section('title', 'Daftar Register Ekspedisi')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-mail-bulk me-2"></i>
                Register Ekspedisi
            </h1>
            <a href="{{ route('expeditions.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>
                Tambah Baru
            </a>
        </div>

        <!-- Search and Filter Form -->
        <div class="card mb-4">
            <div class="card-body search-form">
                <form method="GET" action="{{ route('expeditions.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Pencarian</label>
                        <input type="text"
                               class="form-control"
                               id="search"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Cari nomor dokumen, perihal, atau nama penerima...">
                    </div>

                    <div class="col-md-3">
                        <label for="year" class="form-label">Tahun</label>
                        <select class="form-select" id="year" name="year">
                            <option value="">Semua Tahun</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="classification" class="form-label">Klasifikasi</label>
                        <select class="form-select" id="classification" name="classification">
                            <option value="">Semua Klasifikasi</option>
                            <option value="Biasa" {{ request('classification') == 'Biasa' ? 'selected' : '' }}>Biasa</option>
                            <option value="Rahasia" {{ request('classification') == 'Rahasia' ? 'selected' : '' }}>Rahasia</option>
                            <option value="Lainnya" {{ request('classification') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fas fa-search me-1"></i>
                                Cari
                            </button>
                        </div>
                    </div>
                </form>

                @if(request()->hasAny(['search', 'year', 'classification']))
                    <div class="mt-3">
                        <a href="{{ route('expeditions.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-times me-1"></i>
                            Reset Filter
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Export Buttons -->
        <div class="export-buttons">
            <div class="btn-group" role="group">
                <a href="{{ route('expeditions.export.pdf', request()->query()) }}" class="btn btn-outline-danger">
                    <i class="fas fa-file-pdf me-1"></i>
                    Export PDF
                </a>
                <a href="{{ route('expeditions.export.excel', request()->query()) }}" class="btn btn-outline-success">
                    <i class="fas fa-file-excel me-1"></i>
                    Export Excel
                </a>
            </div>
        </div>

        <!-- Expeditions Table -->
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th width="8%">No. Urut</th>
                                <th width="12%">No. & Tgl Dokumen</th>
                                <th width="15%">Nama Penerima</th>
                                <th width="20%">Perihal</th>
                                <th width="8%">Lampiran</th>
                                <th width="12%">Tgl & Jam Terima</th>
                                <th width="10%">Paraf</th>
                                <th width="8%">Klasifikasi</th>
                                <th width="7%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expeditions as $expedition)
                                <tr>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $expedition->formatted_sequential_number }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <strong>{{ $expedition->document_number }}</strong><br>
                                            <span class="text-muted">{{ $expedition->document_date->format('d/m/Y') }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            {{ Str::limit($expedition->recipient_name, 30) }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            {{ Str::limit($expedition->subject, 40) }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ $expedition->attachments_count }}</span>
                                    </td>
                                    <td>
                                        <div class="small">
                                            {{ $expedition->received_at->format('d/m/Y') }}<br>
                                            <span class="text-muted">{{ $expedition->received_at->format('H:i') }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($expedition->recipient_signature)
                                            <img src="{{ $expedition->recipient_signature }}"
                                                 alt="Paraf"
                                                 class="signature-display"
                                                 data-bs-toggle="tooltip"
                                                 title="{{ $expedition->recipient_name_clear }}">
                                        @else
                                            <span class="text-muted small">Belum ada</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($expedition->classification == 'Biasa')
                                            <span class="badge bg-success">{{ $expedition->classification }}</span>
                                        @elseif($expedition->classification == 'Rahasia')
                                            <span class="badge bg-danger">{{ $expedition->classification }}</span>
                                        @else
                                            <span class="badge bg-warning">{{ $expedition->classification }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('expeditions.show', $expedition) }}"
                                               class="btn btn-outline-info btn-sm"
                                               data-bs-toggle="tooltip"
                                               title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('expeditions.edit', $expedition) }}"
                                               class="btn btn-outline-warning btn-sm"
                                               data-bs-toggle="tooltip"
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-outline-danger btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $expedition->id }}"
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal{{ $expedition->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin menghapus data ekspedisi berikut?</p>
                                                        <div class="alert alert-info">
                                                            <strong>No. Urut:</strong> {{ $expedition->formatted_sequential_number }}<br>
                                                            <strong>Dokumen:</strong> {{ $expedition->document_number }}<br>
                                                            <strong>Perihal:</strong> {{ Str::limit($expedition->subject, 50) }}
                                                        </div>
                                                        <p class="text-danger small">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                                            Tindakan ini tidak dapat dibatalkan!
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <form action="{{ route('expeditions.destroy', $expedition) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="fas fa-trash me-1"></i>
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <h5>Tidak ada data ekspedisi</h5>
                                            <p>Belum ada data ekspedisi yang tersimpan.</p>
                                            <a href="{{ route('expeditions.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-1"></i>
                                                Tambah Data Pertama
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        @if($expeditions->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted small">
                    Menampilkan {{ $expeditions->firstItem() }} - {{ $expeditions->lastItem() }}
                    dari {{ $expeditions->total() }} data
                </div>
                <div>
                    {{ $expeditions->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>
@endpush