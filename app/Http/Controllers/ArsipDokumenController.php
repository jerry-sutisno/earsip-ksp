<?php

namespace App\Http\Controllers;

use App\Models\ArsipDokumen;
use App\Models\KlasifikasiArsip;
use App\Models\LokasiArsip;
use App\Models\TransaksiPinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArsipDokumenController extends Controller
{
    // Kode unit kerja diturunkan dari 2 huruf awal klasifikasi (dummy, sesuaikan jika ada kode resmi)
    private const UNIT_KERJA = ['KP' => 'KSP-KRD', 'KS' => 'KSP-SMP', 'KU' => 'KSP-KEU', 'KE' => 'KSP-UMM'];

    public function index()
    {
        $items = ArsipDokumen::with(['klasifikasi', 'lokasi', 'pinjaman.anggota'])
            ->orderBy('id_arsip')
            ->get();

        return view('arsip.index', compact('items'));
    }

    public function create()
    {
        return view('arsip.create', [
            'klasifikasi' => KlasifikasiArsip::orderBy('kode_klasifikasi')->get(),
            'lokasi' => LokasiArsip::orderBy('kode_lokasi')->get(),
            'pinjaman' => TransaksiPinjaman::with('anggota')->orderBy('no_rekening_pinjaman')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $kode = (string) $request->input('kode_klasifikasi');

        // Berkas pinjaman (KP.*) wajib terhubung ke data transaksi_pinjaman yang benar-benar ada
        $aturanReferensi = str_starts_with($kode, 'KP.')
            ? ['required', 'exists:transaksi_pinjaman,no_rekening_pinjaman']
            : ['nullable', 'max:30'];

        $data = $request->validate([
            'kode_klasifikasi' => ['required', 'exists:klasifikasi_arsip,kode_klasifikasi'],
            'id_lokasi' => ['required', 'exists:lokasi_arsip,id_lokasi'],
            'referensi_nomor' => $aturanReferensi,
            'judul_arsip' => ['required', 'max:150'],
            'tanggal_arsip' => ['required', 'date', 'before_or_equal:today'],
            'media_arsip' => ['nullable', 'in:kertas,digital,hybrid'],
        ], [
            'referensi_nomor.required' => 'Berkas pinjaman (KP.*) wajib diisi nomor rekening pinjamannya.',
            'referensi_nomor.exists' => 'Nomor rekening pinjaman tidak ditemukan di data transaksi pinjaman.',
            'tanggal_arsip.before_or_equal' => 'Tanggal berkas tidak boleh di masa depan.',
        ]);

        $unit = self::UNIT_KERJA[substr($kode, 0, 2)] ?? 'KSP-UMM';
        $prefix = 'ARC-' . $kode . '/' . $unit . '/' . now()->year . '/';

        $idArsip = DB::transaction(function () use ($data, $prefix) {
            // Ambil nomor terakhir dengan prefix yang sama, kunci baris agar tidak bentrok
            $terakhir = DB::table('arsip_dokumen')
                ->where('id_arsip', 'like', $prefix . '%')
                ->lockForUpdate()
                ->max('id_arsip');

            $urut = $terakhir ? ((int) substr($terakhir, strlen($prefix))) + 1 : 1;
            $id = $prefix . str_pad((string) $urut, 4, '0', STR_PAD_LEFT);

            DB::table('arsip_dokumen')->insert([
                'id_arsip' => $id,
                'kode_klasifikasi' => $data['kode_klasifikasi'],
                'id_lokasi' => $data['id_lokasi'],
                'referensi_nomor' => $data['referensi_nomor'] ?? null,
                'judul_arsip' => $data['judul_arsip'],
                'tanggal_arsip' => $data['tanggal_arsip'],
                'media_arsip' => $data['media_arsip'] ?? 'kertas',
                'file_digital_path' => null,
                'status_arsip' => 'aktif',
                'jadwal_retensi_inaktif' => null, // dihitung otomatis di Sprint 3
                'jadwal_penyusutan' => null,
            ]);

            return $id;
        });

        return redirect('/arsip')->with('success', "Arsip terdaftar dengan kode {$idArsip}");
    }
}
