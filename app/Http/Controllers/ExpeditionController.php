<?php

namespace App\Http\Controllers;

use App\Models\Expedition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Exports\ExpeditionsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ExpeditionController extends Controller
{
    /**
     * Display a listing of the expeditions.
     */
    public function index(Request $request)
    {
        $query = $this->getFilteredQuery($request);

        // Paginate results
        $expeditions = $query->paginate(15)->appends($request->query());

        // Get available years for filter
        $years = Expedition::selectRaw('DISTINCT year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('expeditions.index', compact('expeditions', 'years'));
    }

    /**
     * Show the form for creating a new expedition.
     */
    public function create()
    {
        $nextSequentialNumber = Expedition::getNextSequentialNumber();
        $currentYear = Carbon::now()->year;

        return view('expeditions.create', compact('nextSequentialNumber', 'currentYear'));
    }

    /**
     * Store a newly created expedition in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Expedition::validationRules(), Expedition::validationMessages());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $expeditionData = $request->only([
                'document_number',
                'document_date',
                'recipient_name',
                'subject',
                'attachments_count',
                'received_at',
                'recipient_name_clear',
                'classification',
                'notes'
            ]);

            // Handle signature if provided
            if ($request->filled('recipient_signature')) {
                $expeditionData['recipient_signature'] = $request->recipient_signature;
            }

            $expedition = Expedition::create($expeditionData);

            return redirect()->route('expeditions.index')
                ->with('success', 'Data ekspedisi berhasil ditambahkan dengan nomor urut: ' . $expedition->formatted_sequential_number);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified expedition.
     */
    public function show(Expedition $expedition)
    {
        return view('expeditions.show', compact('expedition'));
    }

    /**
     * Show the form for editing the specified expedition.
     */
    public function edit(Expedition $expedition)
    {
        return view('expeditions.edit', compact('expedition'));
    }

    /**
     * Update the specified expedition in storage.
     */
    public function update(Request $request, Expedition $expedition)
    {
        $validator = Validator::make($request->all(), Expedition::validationRules($expedition->id), Expedition::validationMessages());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $expeditionData = $request->only([
                'document_number',
                'document_date',
                'recipient_name',
                'subject',
                'attachments_count',
                'received_at',
                'recipient_name_clear',
                'classification',
                'notes'
            ]);

            // Handle signature if provided
            if ($request->filled('recipient_signature')) {
                $expeditionData['recipient_signature'] = $request->recipient_signature;
            }

            $expedition->update($expeditionData);

            return redirect()->route('expeditions.index')
                ->with('success', 'Data ekspedisi berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified expedition from storage.
     */
    public function destroy(Expedition $expedition)
    {
        try {
            $sequentialNumber = $expedition->formatted_sequential_number;
            $expedition->delete();

            return redirect()->route('expeditions.index')
                ->with('success', 'Data ekspedisi nomor ' . $sequentialNumber . ' berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
    /**
     * Export expeditions to PDF
     */
    public function exportPdf(Request $request)
    {
        // Increase execution time for PDF generation
        set_time_limit(120);

        try {
            $query = $this->getFilteredQuery($request);
            $filters = [];
            $title = 'Semua Data';

            if ($request->filled('search')) {
                $filters['search'] = $request->search;
            }
            if ($request->filled('year')) {
                $filters['year'] = $request->year;
                $title = 'Tahun ' . $request->year;
            }
            if ($request->filled('classification')) {
                $filters['classification'] = $request->classification;
                $title .= ' - ' . $request->classification;
            }

            $expeditions = $query->get();

            // Generate PDF with DomPDF options
            $pdf = Pdf::loadView('expeditions.pdf', compact('expeditions', 'filters', 'title'));
            $pdf->getDomPDF()->set_option('isHtml5ParserEnabled', true);
            $pdf->getDomPDF()->set_option('isRemoteEnabled', true);
            $pdf->setPaper('A4', 'landscape');

            // Generate filename
            $filename = 'register-ekspedisi-' . now()->format('Y-m-d-H-i-s') . '.pdf';

            $result = $pdf->download($filename);

            return $result;
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengekspor PDF: ' . $e->getMessage());
        }
    }
    /**
     * Export expeditions to Excel
     */
    public function exportExcel(Request $request)
    {
        $filename = 'register-ekspedisi-' . now()->format('Y-m-d-H-i-s') . '.xlsx';
        return Excel::download(new ExpeditionsExport($request->query()), $filename);
    }

    /**
     * Get expedition statistics for dashboard
     */
    public function statistics()
    {
        $currentYear = Carbon::now()->year;

        $stats = [
            'total_this_year' => Expedition::byYear($currentYear)->count(),
            'total_this_month' => Expedition::byYear($currentYear)
                ->whereMonth('created_at', Carbon::now()->month)
                ->count(),
            'by_classification' => Expedition::byYear($currentYear)
                ->selectRaw('classification, COUNT(*) as count')
                ->groupBy('classification')
                ->pluck('count', 'classification'),
            'recent_expeditions' => Expedition::byYear($currentYear)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
        ];

        return response()->json($stats);
    }
    /**
     * Export single expedition to PDF
     */
    public function exportSinglePdf(Expedition $expedition)
    {
        // Increase execution time for PDF generation
        set_time_limit(120);

        try {
            // Generate PDF for single expedition
            $pdf = Pdf::loadView('expeditions.single-pdf', compact('expedition'));

            // Set paper size and orientation
            $pdf->setPaper('A4', 'portrait');

            // Sanitize the sequential number for the filename by replacing '/' with '-'
            $safeSequentialNumber = str_replace('/', '-', $expedition->formatted_sequential_number);

            // Generate filename
            $filename = 'ekspedisi-' . $safeSequentialNumber . '-' . now()->format('Y-m-d') . '.pdf';

            $result = $pdf->download($filename);

            return $result;
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengekspor PDF: ' . $e->getMessage());
        }
    }

    /**
     * Get a filtered query builder instance.
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getFilteredQuery(Request $request)
    {
        $query = Expedition::query();

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by year
        if ($request->filled('year')) {
            $query->byYear($request->year);
        }

        // Filter by classification
        if ($request->filled('classification')) {
            $query->byClassification($request->classification);
        }

        // Order by latest first
        return $query->orderBy('created_at', 'desc');
    }
}