@extends('layouts.app')

@section('title', 'Detail Data Ekspedisi')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-eye me-2"></i>
                        Detail Data Ekspedisi
                    </h4>
                    <div class="btn-group" role="group">
                        <a href="{{ route('expeditions.export.single.pdf', $expedition) }}" class="btn btn-outline-danger">
                            <i class="fas fa-file-pdf me-1"></i>
                            Export PDF
                        </a>
                        <a href="{{ route('expeditions.edit', $expedition) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i>
                            Edit
                        </a>
                        <a href="{{ route('expeditions.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <!-- Column 1: Sequential Number -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-primary">
                                <i class="fas fa-hashtag me-1"></i>
                                Nomor Urut
                            </label>
                            <div class="p-3 bg-light rounded">
                                <span class="badge bg-primary fs-6">{{ $expedition->formatted_sequential_number }}</span>
                            </div>
                        </div>

                        <!-- Column 2: Document Number and Date -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-primary">
                                <i class="fas fa-file-alt me-1"></i>
                                Nomor & Tanggal Dokumen
                            </label>
                            <div class="p-3 bg-light rounded">
                                <div class="fw-bold">{{ $expedition->document_number }}</div>
                                <div class="text-muted">{{ $expedition->document_date->format('d F Y') }}</div>
                            </div>
                        </div>

                        <!-- Column 3: Recipient Name -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-primary">
                                <i class="fas fa-user me-1"></i>
                                Nama Pejabat/Jabatan yang Dituju
                            </label>
                            <div class="p-3 bg-light rounded">
                                {{ $expedition->recipient_name }}
                            </div>
                        </div>

                        <!-- Column 4: Subject -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-primary">
                                <i class="fas fa-edit me-1"></i>
                                Perihal Surat/Dokumen
                            </label>
                            <div class="p-3 bg-light rounded">
                                {{ $expedition->subject }}
                            </div>
                        </div>

                        <!-- Column 5: Attachments Count -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-primary">
                                <i class="fas fa-paperclip me-1"></i>
                                Jumlah Lampiran
                            </label>
                            <div class="p-3 bg-light rounded">
                                <span class="badge bg-info fs-6">{{ $expedition->attachments_count }} lampiran</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <!-- Column 6: Received Date and Time -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-primary">
                                <i class="fas fa-clock me-1"></i>
                                Tanggal & Jam Diterima/Dikirim
                            </label>
                            <div class="p-3 bg-light rounded">
                                <div class="fw-bold">{{ $expedition->received_at->format('d F Y') }}</div>
                                <div class="text-muted">{{ $expedition->received_at->format('H:i') }} WIB</div>
                            </div>
                        </div>

                        <!-- Column 7: Recipient Clear Name -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-primary">
                                <i class="fas fa-signature me-1"></i>
                                Nama Jelas Penerima
                            </label>
                            <div class="p-3 bg-light rounded">
                                {{ $expedition->recipient_name_clear }}
                            </div>
                        </div>

                        <!-- Column 7: Signature -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-primary">
                                <i class="fas fa-pen-nib me-1"></i>
                                Paraf Penerima
                            </label>
                            <div class="p-3 bg-light rounded text-center">
                                @if($expedition->recipient_signature)
                                    <img src="{{ $expedition->recipient_signature }}"
                                         alt="Paraf Penerima"
                                         class="img-fluid border rounded"
                                         style="max-height: 150px; max-width: 100%;">
                                @else
                                    <div class="text-muted">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        Paraf belum tersedia
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Column 8: Classification -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-primary">
                                <i class="fas fa-tag me-1"></i>
                                Klasifikasi Surat
                            </label>
                            <div class="p-3 bg-light rounded">
                                @if($expedition->classification == 'Biasa')
                                    <span class="badge bg-success fs-6">{{ $expedition->classification }}</span>
                                @elseif($expedition->classification == 'Rahasia')
                                    <span class="badge bg-danger fs-6">{{ $expedition->classification }}</span>
                                @else
                                    <span class="badge bg-warning fs-6">{{ $expedition->classification }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Column 8: Notes -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-primary">
                                <i class="fas fa-sticky-note me-1"></i>
                                Catatan Penting
                            </label>
                            <div class="p-3 bg-light rounded">
                                @if($expedition->notes)
                                    {{ $expedition->notes }}
                                @else
                                    <span class="text-muted">Tidak ada catatan</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Metadata -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Informasi Sistem
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <strong>Dibuat:</strong> {{ $expedition->created_at->format('d F Y, H:i') }} WIB
                                        </small>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <strong>Terakhir Diupdate:</strong> {{ $expedition->updated_at->format('d F Y, H:i') }} WIB
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('expeditions.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Kembali ke Daftar
                    </a>
                    <div class="btn-group" role="group">
                        <a href="{{ route('expeditions.export.single.pdf', $expedition) }}" class="btn btn-outline-danger">
                            <i class="fas fa-file-pdf me-1"></i>
                            Export PDF
                        </a>
                        <a href="{{ route('expeditions.edit', $expedition) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i>
                            Edit Data
                        </a>
                        <button type="button"
                                class="btn btn-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteModal">
                            <i class="fas fa-trash me-1"></i>
                            Hapus Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
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
@endsection

@push('styles')
<style>
    .badge.fs-6 {
        font-size: 0.9rem !important;
    }

    .bg-light {
        background-color: #f8f9fa !important;
    }

    .form-label.fw-bold {
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .card-body .p-3 {
        min-height: 50px;
        display: flex;
        align-items: center;
    }
</style>
@endpush