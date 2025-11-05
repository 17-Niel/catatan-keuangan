<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FinancialRecord extends Model
{
    use HasFactory;

    // Menentukan nama tabel baru untuk data keuangan
    protected $table = 'financial_records'; 

    protected $fillable = [
        'user_id',
        'type',        // 'pemasukan' atau 'pengeluaran'
        'amount',      // Jumlah uang
        'date',        // Tanggal transaksi
        'description', // Deskripsi catatan
        'cover',       // Gambar bukti (opsional)
    ];

    // Kolom yang harus di-cast ke tipe data tertentu
    protected $casts = [
        'date' => 'date',
    ];

    // Relasi: Setiap catatan dimiliki oleh satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor: Mendapatkan URL cover
    public function getCoverUrlAttribute()
    {
        return $this->cover ? Storage::url($this->cover) : null;
    }
}