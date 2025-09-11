<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Expedition extends Model
{
    use HasFactory;

    protected $fillable = [
        'sequential_number',
        'year',
        'document_number',
        'document_date',
        'recipient_name',
        'subject',
        'attachments_count',
        'received_at',
        'recipient_signature',
        'recipient_name_clear',
        'classification',
        'notes'
    ];

    protected $casts = [
        'document_date' => 'date',
        'received_at' => 'datetime',
        'year' => 'integer',
        'sequential_number' => 'integer',
        'attachments_count' => 'integer'
    ];

    /**
     * Boot the model and set up event listeners
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($expedition) {
            if (!$expedition->year) {
                $expedition->year = Carbon::now()->year;
            }

            if (!$expedition->sequential_number) {
                $expedition->sequential_number = static::getNextSequentialNumber($expedition->year);
            }
        });
    }

    /**
     * Get the next sequential number for the given year
     */
    public static function getNextSequentialNumber($year = null)
    {
        if (!$year) {
            $year = Carbon::now()->year;
        }

        $lastExpedition = static::where('year', $year)
            ->orderBy('sequential_number', 'desc')
            ->first();

        return $lastExpedition ? $lastExpedition->sequential_number + 1 : 1;
    }

    /**
     * Get formatted sequential number with year
     */
    public function getFormattedSequentialNumberAttribute()
    {
        return sprintf('%03d/%d', $this->sequential_number, $this->year);
    }

    /**
     * Scope to filter by year
     */
    public function scopeByYear($query, $year)
    {
        return $query->where('year', $year);
    }

    /**
     * Scope to filter by classification
     */
    public function scopeByClassification($query, $classification)
    {
        return $query->where('classification', $classification);
    }

    /**
     * Scope to search by document number or subject
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('document_number', 'like', "%{$search}%")
                ->orWhere('subject', 'like', "%{$search}%")
                ->orWhere('recipient_name', 'like', "%{$search}%")
                ->orWhere('recipient_name_clear', 'like', "%{$search}%");
        });
    }

    /**
     * Get validation rules for creating/updating expeditions
     */
    public static function validationRules($id = null)
    {
        return [
            'document_number' => 'required|string|max:255',
            'document_date' => 'required|date',
            'recipient_name' => 'required|string|max:255',
            'subject' => 'required|string',
            'attachments_count' => 'required|integer|min:0',
            'received_at' => 'required|date',
            'recipient_name_clear' => 'required|string|max:255',
            'classification' => 'required|in:Biasa,Rahasia,Lainnya',
            'notes' => 'nullable|string'
        ];
    }

    /**
     * Get validation messages
     */
    public static function validationMessages()
    {
        return [
            'document_number.required' => 'Nomor dokumen harus diisi.',
            'document_date.required' => 'Tanggal dokumen harus diisi.',
            'recipient_name.required' => 'Nama penerima harus diisi.',
            'subject.required' => 'Perihal surat harus diisi.',
            'attachments_count.required' => 'Jumlah lampiran harus diisi.',
            'attachments_count.integer' => 'Jumlah lampiran harus berupa angka.',
            'attachments_count.min' => 'Jumlah lampiran tidak boleh kurang dari 0.',
            'received_at.required' => 'Tanggal dan jam terima harus diisi.',
            'recipient_name_clear.required' => 'Nama jelas penerima harus diisi.',
            'classification.required' => 'Klasifikasi surat harus dipilih.',
            'classification.in' => 'Klasifikasi surat harus salah satu dari: Biasa, Rahasia, Lainnya.'
        ];
    }
}