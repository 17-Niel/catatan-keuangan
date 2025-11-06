<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FinancialRecord extends Model
{
    use HasFactory;

    protected $table = 'financial_records'; 

    protected $fillable = [
        'user_id',
        'type',
        'amount', 
        'date',
        'description',
        'cover',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    protected $appends = ['cover_url', 'formatted_amount'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor: URL cover yang benar
    public function getCoverUrlAttribute()
    {
        if (!$this->cover) {
            return null;
        }
        
        return Storage::disk('public')->url($this->cover);
    }

    // Accessor: Amount yang diformat
    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    // Scope untuk pemasukan
    public function scopePemasukan($query)
    {
        return $query->where('type', 'pemasukan');
    }

    // Scope untuk pengeluaran
    public function scopePengeluaran($query)
    {
        return $query->where('type', 'pengeluaran');
    }

    // Scope untuk user tertentu
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}