<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // PERUBAHAN UTAMA: Nama tabel diganti menjadi financial_records
        Schema::create('financial_records', function (Blueprint $table) {
            $table->id();
            // foreignId memastikan hubungan ke tabel 'users' untuk login
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Kolom Catatan Keuangan
            $table->enum('type', ['pemasukan', 'pengeluaran']); // Jenis transaksi
            $table->decimal('amount', 15, 2); // Jumlah uang (dengan 2 angka di belakang koma)
            $table->date('date'); // Tanggal transaksi
            
            $table->text('description')->nullable(); // Deskripsi (menggantikan 'name')
            $table->string('cover')->nullable(); // Gambar bukti (dipertahankan)
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_records');
    }
};