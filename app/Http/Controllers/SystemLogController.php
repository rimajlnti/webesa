<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SystemLogController extends Controller
{
    // Method index untuk menampilkan halaman logs sistem
    public function index()
    {
        // Contoh menampilkan view system.logs (buat file ini nanti di resources/views/system/logs.blade.php)
        return view('system.logs');
    }
}
