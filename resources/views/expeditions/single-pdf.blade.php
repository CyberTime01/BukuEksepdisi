<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Ekspedisi - {{ $expedition->formatted_sequential_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 30px;
            line-height: 1.6;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 3px solid #333;
            padding-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
            color: #333;
            text-transform: uppercase;
        }

        .header h2 {
            margin: 10px 0 0 0;
            font-size: 18px;
            font-weight: normal;
            color: #666;
        }

        .sequential-number {
            background-color: #007bff;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            display: inline-block;
            margin: 15px 0;
        }

        .content-section {
            margin-bottom: 30px;
        }

        .row {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .col {
            display: table-cell;
            width: 50%;
            padding-right: 20px;
            vertical-align: top;
        }

        .field-group {
            margin-bottom: 25px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            background-color: #f9f9f9;
        }

        .field-label {
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
            display: block;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .field-value {
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
        }

        .field-value.large {
            font-size: 16px;
            font-weight: 500;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
            color: white;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-danger {
            background-color: #dc3545;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-info {
            background-color: #17a2b8;
        }

        .signature-section {
            text-align: center;
            margin: 20px 0;
        }

        .signature-image {
            width: 200px;
            height: 150px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            object-fit: contain;
        }

        .metadata-section {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 11px;
            color: #888;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .text-center {
            text-align: center;
        }

        .text-muted {
            color: #666;
        }

        .full-width {
            width: 100%;
        }

        @media print {
            body {
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="content-section">
        <div class="row">
            <div class="col">
                <div class="field-group">
                    <span class="field-label">
                        <i class="fas fa-file-alt"></i> Nomor Dokumen
                    </span>
                    <div class="field-value large">{{ $expedition->document_number }}</div>
                </div>

                <div class="field-group">
                    <span class="field-label">
                        <i class="fas fa-calendar"></i> Tanggal Dokumen
                    </span>
                    <div class="field-value">{{ $expedition->document_date->format('d F Y') }}</div>
                </div>

                <div class="field-group">
                    <span class="field-label">
                        <i class="fas fa-user"></i> Nama Pejabat/Jabatan yang Dituju
                    </span>
                    <div class="field-value">{{ $expedition->recipient_name }}</div>
                </div>

                <div class="field-group">
                    <span class="field-label">
                        <i class="fas fa-paperclip"></i> Jumlah Lampiran
                    </span>
                    <div class="field-value">
                        <span class="badge badge-info">{{ $expedition->attachments_count }} lampiran</span>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="field-group">
                    <span class="field-label">
                        <i class="fas fa-clock"></i> Tanggal & Jam Diterima/Dikirim
                    </span>
                    <div class="field-value">
                        {{ $expedition->received_at->format('d F Y') }}<br>
                        <span class="text-muted">{{ $expedition->received_at->format('H:i') }} WIB</span>
                    </div>
                </div>

                <div class="field-group">
                    <span class="field-label">
                        <i class="fas fa-signature"></i> Nama Jelas Penerima
                    </span>
                    <div class="field-value">{{ $expedition->recipient_name_clear }}</div>
                </div>

                <div class="field-group">
                    <span class="field-label">
                        <i class="fas fa-tag"></i> Klasifikasi Surat
                    </span>
                    <div class="field-value">
                        @if($expedition->classification == 'Biasa')
                            <span class="badge badge-success">{{ $expedition->classification }}</span>
                        @elseif($expedition->classification == 'Rahasia')
                            <span class="badge badge-danger">{{ $expedition->classification }}</span>
                        @else
                            <span class="badge badge-warning">{{ $expedition->classification }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="field-group full-width">
            <span class="field-label">
                <i class="fas fa-edit"></i> Perihal Surat/Dokumen
            </span>
            <div class="field-value">{{ $expedition->subject }}</div>
        </div>

        @if($expedition->notes)
        <div class="field-group full-width">
            <span class="field-label">
                <i class="fas fa-sticky-note"></i> Catatan Penting
            </span>
            <div class="field-value">{{ $expedition->notes }}</div>
        </div>
        @endif

        <div class="field-group full-width">
            <span class="field-label">
                <i class="fas fa-pen-nib"></i> Paraf Penerima
            </span>
            <div class="signature-section">
                @if($expedition->recipient_signature)
                    <img src="{{ $expedition->recipient_signature }}"
                         alt="Paraf Penerima"
                         class="signature-image">
                    <div style="margin-top: 10px; font-size: 12px; color: #666;">
                        <strong>{{ $expedition->recipient_name_clear }}</strong>
                    </div>
                @else
                    <div class="text-muted" style="padding: 30px;">
                        <em>Paraf belum tersedia</em>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="metadata-section">
        <div class="row">
            <div class="col">
                <strong>Data dibuat:</strong><br>
                {{ $expedition->created_at->format('d F Y, H:i') }} WIB
            </div>
            <div class="col">
                <strong>Terakhir diupdate:</strong><br>
                {{ $expedition->updated_at->format('d F Y, H:i') }} WIB
            </div>
        </div>
    </div>

    <div class="footer">
        <p>
            <strong>Register Ekspedisi - Detail Lengkap</strong><br>
            Dicetak pada {{ now()->format('d F Y, H:i') }} WIB<br>
            Nomor Urut: {{ $expedition->formatted_sequential_number }}
        </p>
    </div>
</body>
</html>
