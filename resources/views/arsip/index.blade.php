@extends('layouts.app')
@section('title', 'Daftar Arsip')
@section('content')
<div class="card">
    <div class="bar">
        <h2>Daftar Arsip</h2>
        @if (auth()->user()->role === 'petugas')
            <a class="btn" href="{{ route('arsip.create') }}">+ Registrasi Arsip</a>
        @endif
    </div>
    <table>
        <thead>
            <tr>
                <th>Kode arsip</th>
                <th>Judul</th>
                <th>Klasifikasi</th>
                <th>Lokasi</th>
                <th>Referensi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $a)
                <tr>
                    <td><strong>{{ $a->id_arsip }}</strong></td>
                    <td>{{ $a->judul_arsip }}</td>
                    <td>{{ $a->kode_klasifikasi }} @if($a->klasifikasi) ({{ $a->klasifikasi->jenis_berkas }}) @endif</td>
                    <td>{{ $a->lokasi?->kode_lokasi }}<br><small>{{ $a->lokasi?->gedung }}</small></td>
                    <td>
                        {{ $a->referensi_nomor ?? '-' }}
                        @if ($a->pinjaman && $a->pinjaman->anggota)
                            <br><small>{{ $a->pinjaman->anggota->nama_lengkap }} ({{ $a->pinjaman->status_pinjaman }})</small>
                        @endif
                    </td>
                    <td>{{ $a->status_arsip }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
