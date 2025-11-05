<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FinancialRecord; // Import model baru

class FinancialRecordController extends Controller // Ganti nama kelas
{
    public function index()
    {
        return view('pages.app.home');
    }

    // Metode untuk menampilkan detail catatan keuangan
    public function detail(FinancialRecord $financialRecord)
    {
        // Pastikan Anda membuat folder view baru di resources/views/pages/app/financial-records
        return view('pages.app.financial_records.detail', [
            'recordId' => $financialRecord->id
        ]);
    }
}