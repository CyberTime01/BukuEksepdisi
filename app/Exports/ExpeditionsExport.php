<?php

namespace App\Exports;

use App\Models\Expedition;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExpeditionsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = Expedition::query();

        // Apply filters
        if (!empty($this->filters['search'])) {
            $query->search($this->filters['search']);
        }

        if (!empty($this->filters['year'])) {
            $query->byYear($this->filters['year']);
        }

        if (!empty($this->filters['classification'])) {
            $query->byClassification($this->filters['classification']);
        }

        return $query->orderBy('created_at', 'desc');
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No. Urut',
            'No. Dokumen',
            'Tgl. Dokumen',
            'Nama Pejabat/Jabatan yang Dituju',
            'Perihal Surat/Dokumen',
            'Jumlah Lampiran',
            'Tgl. Diterima/Dikirim',
            'Jam Diterima/Dikirim',
            'Nama Jelas Penerima',
            'Klasifikasi Surat',
            'Catatan Penting',
            'Dibuat Pada',
        ];
    }

    /**
     * @param Expedition $expedition
     * @return array
     */
    public function map($expedition): array
    {
        return [
            $expedition->formatted_sequential_number,
            $expedition->document_number,
            $expedition->document_date->format('d-m-Y'),
            $expedition->recipient_name,
            $expedition->subject,
            $expedition->attachments_count,
            $expedition->received_at->format('d-m-Y'),
            $expedition->received_at->format('H:i'),
            $expedition->recipient_name_clear,
            $expedition->classification,
            $expedition->notes,
            $expedition->created_at->format('d-m-Y H:i:s'),
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold.
            1    => ['font' => ['bold' => true]],
        ];
    }
}
