<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArsipDokumenController extends Controller
{
    public function index()
    {
        return view('halaman', ['judul' => 'Daftar Arsip']);
    }

    public function create()
    {
        return view('halaman', ['judul' => 'Tambah Arsip']);
    }

    public function store(Request $request)
    {
        return redirect()->route('arsip.index');
    }
}
