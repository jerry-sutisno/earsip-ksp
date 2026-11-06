@extends('layouts.app')
@section('title', 'Registrasi Arsip')
@section('content')
<div class="card" style="max-width: 640px;">
    <h2>Registrasi Arsip Baru</h2>
    @include('partials.errors')
    <form method="POST" action="{{ route('arsip.store') }}">
        @csrf

        <label>Klasifikasi</label>
        <select name="kode_klasifikasi" required>
            <option value="">- pilih -</option>
            @foreach ($klasifikasi as $k)
                <option value="{{ $k->kode_klasifikasi }}" @selected(old('kode_klasifikasi') === $k->kode_klasifikasi)>
                    {{ $k->kode_klasifikasi }} - {{ $k->nama_klasifikasi }} ({{ $k->jenis_berkas }})
                </option>
            @endforeach
        </select>

        <label>Lokasi simpan fisik</label>
        <select name="id_lokasi" required>
            <option value="">- pilih -</option>
            @foreach ($lokasi as $l)
                <option value="{{ $l->id_lokasi }}" @selected((string) old('id_lokasi') === (string) $l->id_lokasi)>
                    {{ $l->kode_lokasi }} - {{ $l->gedung }}
                </option>
            @endforeach
        </select>

        <label>Nomor rekening pinjaman / nomor simpanan (wajib untuk klasifikasi KP.*)</label>
        <input name="referensi_nomor" list="daftar-pinjaman" value="{{ old('referensi_nomor') }}" autocomplete="off">
        <datalist id="daftar-pinjaman">
            @foreach ($pinjaman as $p)
                <option value="{{ $p->no_rekening_pinjaman }}">{{ $p->anggota->nama_lengkap }} ({{ $p->status_pinjaman }})</option>
            @endforeach
        </datalist>

        <label>Judul / uraian berkas</label>
        <input name="judul_arsip" value="{{ old('judul_arsip') }}" required>

        <label>Tanggal berkas</label>
        <input type="date" name="tanggal_arsip" value="{{ old('tanggal_arsip') }}" required>

        <label>Media</label>
        <select name="media_arsip">
            @foreach (['kertas', 'digital', 'hybrid'] as $o)
                <option value="{{ $o }}" @selected(old('media_arsip', 'kertas') === $o)>{{ $o }}</option>
            @endforeach
        </select>

        <p style="font-size:13px;color:#666">Kode arsip dibuat otomatis saat disimpan. Upload PDF dan hitung JRA dikerjakan di Sprint 3.</p>
        <button type="submit">Simpan</button>
        <a href="/arsip">Batal</a>
    </form>
</div>
@endsection