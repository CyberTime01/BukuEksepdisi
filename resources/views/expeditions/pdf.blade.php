<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Ekspedisi - <?php echo e(now()->format('d F Y')); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .header h2 {
            margin: 5px 0 0 0;
            font-size: 14px;
            font-weight: normal;
            color: #666;
        }

        .info-section {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
        }

        .info-left, .info-right {
            width: 48%;
        }

        .info-item {
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: bold;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 10px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            color: white;
        }

        .badge-primary {
            background-color: #007bff;
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

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }

        .signature-display {
            width: 80px;
            height: 40px;
            display: block;
            margin: 0 auto;
            object-fit: contain;
        }

        .page-break {
            page-break-after: always;
        }

        @media print {
            body {
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REGISTER EKSPEDISI</h1>
        <h2>Daftar Ekspedisi <?php echo e($title ?? 'Semua Data'); ?></h2>
        <?php if(isset($filters) && !empty(array_filter($filters))): ?>
            <div style="margin-top: 10px; font-size: 11px;">
                <strong>Filter:</strong>
                <?php if(!empty($filters['search'])): ?>
                    Pencarian: "<?php echo e($filters['search']); ?>" |
                <?php endif; ?>
                <?php if(!empty($filters['year'])): ?>
                    Tahun: <?php echo e($filters['year']); ?> |
                <?php endif; ?>
                <?php if(!empty($filters['classification'])): ?>
                    Klasifikasi: <?php echo e($filters['classification']); ?> |
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="info-section">
        <div class="info-left">
            <div class="info-item">
                <span class="info-label">Tanggal Cetak:</span> <?php echo e(now()->format('d F Y, H:i')); ?> WIB
            </div>
            <div class="info-item">
                <span class="info-label">Total Data:</span> <?php echo e($expeditions->count()); ?> ekspedisi
            </div>
        </div>
        <div class="info-right">
            <?php if(isset($filters['year'])): ?>
                <div class="info-item">
                    <span class="info-label">Tahun:</span> <?php echo e($filters['year']); ?>

                </div>
            <?php endif; ?>
            <?php if(isset($filters['classification'])): ?>
                <div class="info-item">
                    <span class="info-label">Klasifikasi:</span> <?php echo e($filters['classification']); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="8%">No. Urut</th>
                <th width="12%">No. & Tgl<br>Dokumen</th>
                <th width="15%">Nama Penerima</th>
                <th width="20%">Perihal</th>
                <th width="8%">Lampiran</th>
                <th width="12%">Tgl & Jam<br>Terima</th>
                <th width="10%">Paraf</th>
                <th width="8%">Klasifikasi</th>
                <th width="7%">Catatan</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $expeditions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expedition): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="text-center">
                        <span class="badge badge-primary"><?php echo e($expedition->formatted_sequential_number); ?></span>
                    </td>
                    <td>
                        <strong><?php echo e($expedition->document_number); ?></strong><br>
                        <small><?php echo e($expedition->document_date->format('d/m/Y')); ?></small>
                    </td>
                    <td><?php echo e($expedition->recipient_name); ?></td>
                    <td><?php echo e($expedition->subject); ?></td>
                    <td class="text-center">
                        <span class="badge badge-info"><?php echo e($expedition->attachments_count); ?></span>
                    </td>
                    <td>
                        <?php echo e($expedition->received_at->format('d/m/Y')); ?><br>
                        <small><?php echo e($expedition->received_at->format('H:i')); ?></small>
                    </td>
                    <td class="text-center">
                        <?php if($expedition->recipient_signature): ?>
                            <img src="<?php echo e($expedition->recipient_signature); ?>" alt="Paraf" class="signature-display">
                        <?php else: ?>
                            <small>-</small>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <?php if($expedition->classification == 'Biasa'): ?>
                            <span class="badge badge-success"><?php echo e($expedition->classification); ?></span>
                        <?php elseif($expedition->classification == 'Rahasia'): ?>
                            <span class="badge badge-danger"><?php echo e($expedition->classification); ?></span>
                        <?php else: ?>
                            <span class="badge badge-warning"><?php echo e($expedition->classification); ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <small><?php echo e($expedition->notes ? Str::limit($expedition->notes, 50) : '-'); ?></small>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="9" class="text-center">
                        <em>Tidak ada data ekspedisi yang tersedia</em>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>
            <strong>Register Ekspedisi</strong><br>
            Dicetak pada <?php echo e(now()->format('d F Y, H:i')); ?> WIB<br>
            Halaman <?php echo '{PAGE_NUM}'; ?> dari <?php echo '{PAGE_COUNT}'; ?>
        </p>
    </div>
</body>
</html>
<?php /**PATH D:\buku-ekspedisi\resources\views/expeditions/pdf.blade.php ENDPATH**/ ?>
