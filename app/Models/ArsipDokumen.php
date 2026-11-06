<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsipDokumen extends Model
{
    protected $table = 'arsip_dokumen';
    protected $primaryKey = 'id_arsip';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $guarded = [];

    public function klasifikasi()
    {
        return $this->belongsTo(KlasifikasiArsip::class, 'kode_klasifikasi', 'kode_klasifikasi');
    }

    public function lokasi()
    {
        return $this->belongsTo(LokasiArsip::class, 'id_lokasi', 'id_lokasi');
    }

    // referensi_nomor bukan FK, tapi bisa dipakai sebagai relasi untuk berkas pinjaman (KP.*)
    public function pinjaman()
    {
        return $this->belongsTo(TransaksiPinjaman::class, 'referensi_nomor', 'no_rekening_pinjaman');
    }
}