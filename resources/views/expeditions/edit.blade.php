@extends('layouts.app')

@section('title', 'Edit Data Ekspedisi')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Edit Data Ekspedisi
                    </h4>
                    <a href="{{ route('expeditions.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <div class="card-body">
                <!-- Sequential Number Info -->
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Nomor Urut:</strong> {{ $expedition->formatted_sequential_number }}
                </div>

                <form action="{{ route('expeditions.update', $expedition) }}" method="POST" id="expeditionForm">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Column 2: Document Number and Date -->
                        <div class="col-md-6 mb-3">
                            <label for="document_number" class="form-label">
                                <i class="fas fa-file-alt me-1"></i>
                                Nomor Dokumen <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('document_number') is-invalid @enderror"
                                   id="document_number"
                                   name="document_number"
                                   value="{{ old('document_number', $expedition->document_number) }}"
                                   placeholder="Contoh: DOC/001/2024"
                                   required>
                            @error('document_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="document_date" class="form-label">
                                <i class="fas fa-calendar me-1"></i>
                                Tanggal Dokumen <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                   class="form-control @error('document_date') is-invalid @enderror"
                                   id="document_date"
                                   name="document_date"
                                   value="{{ old('document_date', $expedition->document_date->format('Y-m-d')) }}"
                                   required>
                            @error('document_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Column 3: Recipient Name -->
                    <div class="mb-3">
                        <label for="recipient_name" class="form-label">
                            <i class="fas fa-user me-1"></i>
                            Nama Pejabat/Jabatan yang Dituju <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('recipient_name') is-invalid @enderror"
                               id="recipient_name"
                               name="recipient_name"
                               value="{{ old('recipient_name', $expedition->recipient_name) }}"
                               placeholder="Contoh: Kepala Bagian Administrasi"
                               required>
                        @error('recipient_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Column 4: Subject -->
                    <div class="mb-3">
                        <label for="subject" class="form-label">
                            <i class="fas fa-edit me-1"></i>
                            Perihal Surat/Dokumen <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control @error('subject') is-invalid @enderror"
                                  id="subject"
                                  name="subject"
                                  rows="3"
                                  placeholder="Jelaskan perihal atau isi pokok surat/dokumen..."
                                  required>{{ old('subject', $expedition->subject) }}</textarea>
                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <!-- Column 5: Attachments Count -->
                        <div class="col-md-6 mb-3">
                            <label for="attachments_count" class="form-label">
                                <i class="fas fa-paperclip me-1"></i>
                                Jumlah Lampiran <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                   class="form-control @error('attachments_count') is-invalid @enderror"
                                   id="attachments_count"
                                   name="attachments_count"
                                   value="{{ old('attachments_count', $expedition->attachments_count) }}"
                                   min="0"
                                   required>
                            @error('attachments_count')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Column 6: Received Date and Time -->
                        <div class="col-md-6 mb-3">
                            <label for="received_at" class="form-label">
                                <i class="fas fa-clock me-1"></i>
                                Tanggal & Jam Diterima/Dikirim <span class="text-danger">*</span>
                            </label>
                            <input type="datetime-local"
                                   class="form-control @error('received_at') is-invalid @enderror"
                                   id="received_at"
                                   name="received_at"
                                   value="{{ old('received_at', $expedition->received_at->format('Y-m-d\TH:i')) }}"
                                   required>
                            @error('received_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Column 7: Recipient Clear Name -->
                    <div class="mb-3">
                        <label for="recipient_name_clear" class="form-label">
                            <i class="fas fa-signature me-1"></i>
                            Nama Jelas Penerima <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('recipient_name_clear') is-invalid @enderror"
                               id="recipient_name_clear"
                               name="recipient_name_clear"
                               value="{{ old('recipient_name_clear', $expedition->recipient_name_clear) }}"
                               placeholder="Contoh: Dr. Ahmad Suryanto, M.Si"
                               required>
                        @error('recipient_name_clear')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Column 7: Signature Pad -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-pen-nib me-1"></i>
                            Paraf Penerima
                        </label>

                        @if($expedition->recipient_signature)
                            <div class="current-signature mb-3">
                                <label class="form-label small text-muted">Paraf Saat Ini:</label>
                                <div class="p-3 border rounded bg-light">
                                    <img src="{{ $expedition->recipient_signature }}"
                                         alt="Paraf Saat Ini"
                                         class="signature-display"
                                         style="max-height: 100px;">
                                </div>
                            </div>
                        @endif

                        <div class="signature-container">
                            <canvas id="signaturePad" class="signature-pad" width="600" height="200"></canvas>
                            <div class="signature-controls mt-2">
                                <button type="button" id="clearSignature" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-eraser me-1"></i>
                                    Hapus Paraf
                                </button>
                                @if($expedition->recipient_signature)
                                    <button type="button" id="loadCurrentSignature" class="btn btn-outline-info btn-sm ms-2">
                                        <i class="fas fa-undo me-1"></i>
                                        Muat Paraf Lama
                                    </button>
                                @endif
                                <small class="text-muted ms-3">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Gunakan mouse atau sentuh layar untuk membuat paraf baru
                                </small>
                            </div>
                        </div>
                        <input type="hidden" id="recipient_signature" name="recipient_signature" value="{{ $expedition->recipient_signature }}">
                    </div>

                    <div class="row">
                        <!-- Column 8: Classification -->
                        <div class="col-md-6 mb-3">
                            <label for="classification" class="form-label">
                                <i class="fas fa-tag me-1"></i>
                                Klasifikasi Surat <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('classification') is-invalid @enderror"
                                    id="classification"
                                    name="classification"
                                    required>
                                <option value="">Pilih Klasifikasi</option>
                                <option value="Biasa" {{ old('classification', $expedition->classification) == 'Biasa' ? 'selected' : '' }}>Biasa</option>
                                <option value="Rahasia" {{ old('classification', $expedition->classification) == 'Rahasia' ? 'selected' : '' }}>Rahasia</option>
                                <option value="Lainnya" {{ old('classification', $expedition->classification) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('classification')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Column 8: Notes -->
                        <div class="col-md-6 mb-3">
                            <label for="notes" class="form-label">
                                <i class="fas fa-sticky-note me-1"></i>
                                Catatan Penting
                            </label>
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                      id="notes"
                                      name="notes"
                                      rows="3"
                                      placeholder="Catatan tambahan untuk monitoring tindak lanjut...">{{ old('notes', $expedition->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('expeditions.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>
                            Update Data Ekspedisi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .signature-container {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 15px;
        background-color: #f8f9fa;
    }

    .signature-pad {
        border: 1px solid #ced4da;
        border-radius: 4px;
        background-color: white;
        cursor: crosshair;
        display: block;
        margin: 0 auto;
        max-width: 100%;
    }

    .current-signature {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 10px;
    }

    @media (max-width: 768px) {
        .signature-pad {
            width: 100%;
            height: 150px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize signature pad
    const canvas = document.getElementById('signaturePad');
    const signaturePad = new SignaturePad(canvas, {
        backgroundColor: 'rgba(255, 255, 255, 0)',
        penColor: 'rgb(0, 0, 0)',
        velocityFilterWeight: 0.7,
        minWidth: 0.5,
        maxWidth: 2.5,
        throttle: 16,
        minPointDistance: 3,
    });

    // Clear signature button
    document.getElementById('clearSignature').addEventListener('click', function() {
        signaturePad.clear();
        document.getElementById('recipient_signature').value = '';
    });

    // Load current signature button (if exists)
    const loadCurrentBtn = document.getElementById('loadCurrentSignature');
    if (loadCurrentBtn) {
        loadCurrentBtn.addEventListener('click', function() {
            const currentSignature = '{{ $expedition->recipient_signature }}';
            if (currentSignature) {
                const img = new Image();
                img.onload = function() {
                    const ctx = canvas.getContext('2d');
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                    document.getElementById('recipient_signature').value = currentSignature;
                };
                img.src = currentSignature;
            }
        });
    }

    // Handle form submission
    document.getElementById('expeditionForm').addEventListener('submit', function(e) {
        // Save signature data if not empty
        if (!signaturePad.isEmpty()) {
            const signatureData = signaturePad.toDataURL('image/png');
            document.getElementById('recipient_signature').value = signatureData;
        }
    });

    // Resize canvas to fit container
    function resizeCanvas() {
        const container = canvas.parentElement;
        const containerWidth = container.clientWidth - 30; // Account for padding
        const ratio = Math.min(containerWidth / 600, 1);

        canvas.width = 600 * ratio;
        canvas.height = 200 * ratio;
        canvas.style.width = (600 * ratio) + 'px';
        canvas.style.height = (200 * ratio) + 'px';

        const context = canvas.getContext('2d');
        context.scale(ratio, ratio);

        signaturePad.clear();
    }

    // Initial resize and window resize handler
    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);

    // Form validation enhancement
    const form = document.getElementById('expeditionForm');
    const requiredFields = form.querySelectorAll('[required]');

    requiredFields.forEach(field => {
        field.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    });
});
</script>
@endpush