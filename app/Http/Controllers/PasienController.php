<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\JadwalPraktek;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PasienController extends Controller
{
    /**
     * Dashboard pasien
     */
    public function dashboard()
    {
        $user = auth()->user();

        $antrianAktif = Pendaftaran::with(['dokter', 'jadwal'])
            ->where('user_id', $user->id)
            ->whereDate('tanggal_daftar', today())
            ->whereIn('status', ['menunggu', 'dipanggil'])
            ->first();

        $posisiAntrian = null;
        if ($antrianAktif) {
            $posisiAntrian = Pendaftaran::where('dokter_id', $antrianAktif->dokter_id)
                ->whereDate('tanggal_daftar', today())
                ->whereIn('status', ['menunggu', 'dipanggil'])
                ->where('no_antrian', '<=', $antrianAktif->no_antrian)
                ->count();
        }

        $riwayat = Pendaftaran::with(['dokter', 'jadwal'])
            ->where('user_id', $user->id)
            ->orderByDesc('tanggal_daftar')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $totalPendaftaran = Pendaftaran::where('user_id', $user->id)->count();
        $totalSelesai     = Pendaftaran::where('user_id', $user->id)->where('status', 'selesai')->count();
        $totalMenunggu    = Pendaftaran::where('user_id', $user->id)->whereIn('status', ['menunggu', 'dipanggil'])->count();

        return view('pasien.dashboard', compact(
            'antrianAktif', 'posisiAntrian', 'riwayat',
            'totalPendaftaran', 'totalSelesai', 'totalMenunggu',
        ));
    }

    /**
     * List dokter
     */
    public function dokter()
    {
        $dokter = Dokter::with(['jadwalPraktek' => function ($q) {
            $q->where('aktif', true)
              ->orderByRaw("FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')");
        }])->get();

        return view('pasien.dokter', compact('dokter'));
    }

    /**
     * Form daftar antrian - pilih jadwal & tanggal untuk dokter tertentu
     */
    public function daftarForm(Dokter $dokter)
    {
        $jadwalAktif = $dokter->jadwalPraktek()
            ->where('aktif', true)
            ->orderByRaw("FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')")
            ->get();

        // Buat daftar 14 hari ke depan yang tersedia berdasarkan jadwal dokter
        $hariIndonesia = [
            0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu',
            4 => 'Kamis',  5 => 'Jumat', 6 => 'Sabtu',
        ];

        $hariTersedia = $jadwalAktif->pluck('hari')->toArray();

        $tanggalTersedia = [];
        for ($i = 0; $i <= 13; $i++) {
            $tanggal   = Carbon::today()->addDays($i);
            $hariNama  = $hariIndonesia[$tanggal->dayOfWeek];

            if (in_array($hariNama, $hariTersedia)) {
                // Cari jadwal yang sesuai hari ini
                $jadwal = $jadwalAktif->firstWhere('hari', $hariNama);
                $sisa   = $jadwal->sisaKuota($tanggal->toDateString());

                if ($sisa > 0) {
                    $tanggalTersedia[] = [
                        'tanggal'    => $tanggal->toDateString(),
                        'label'      => $tanggal->translatedFormat('l, d F Y'),
                        'hari'       => $hariNama,
                        'jadwal_id'  => $jadwal->id,
                        'jam_mulai'  => $jadwal->jam_mulai,
                        'jam_selesai'=> $jadwal->jam_selesai,
                        'sisa_kuota' => $sisa,
                        'kuota'      => $jadwal->kuota,
                    ];
                }
            }
        }

        return view('pasien.daftar', compact('dokter', 'tanggalTersedia'));
    }

    /**
     * Simpan pendaftaran antrian
     */
    public function daftarStore(Request $request, Dokter $dokter)
    {
        $request->validate([
            'jadwal_id'    => 'required|exists:jadwal_praktek,id',
            'tanggal_daftar' => 'required|date|after_or_equal:today',
            'keluhan'      => 'nullable|string|max:500',
        ]);

        $user = auth()->user();

        // Cek apakah sudah punya antrian aktif di dokter yg sama hari yg sama
        $sudahDaftar = Pendaftaran::where('user_id', $user->id)
            ->where('dokter_id', $dokter->id)
            ->whereDate('tanggal_daftar', $request->tanggal_daftar)
            ->whereIn('status', ['menunggu', 'dipanggil'])
            ->exists();

        if ($sudahDaftar) {
            return back()->with('error', 'Anda sudah memiliki antrian aktif untuk dokter ini pada tanggal tersebut.');
        }

        // Ambil jadwal & validasi hari sesuai tanggal
        $jadwal = JadwalPraktek::findOrFail($request->jadwal_id);

        if (!$jadwal->sesuaiTanggal($request->tanggal_daftar)) {
            return back()->with('error', 'Tanggal yang dipilih tidak sesuai dengan jadwal praktek dokter.');
        }

        // Cek sisa kuota
        $sisa = $jadwal->sisaKuota($request->tanggal_daftar);
        if ($sisa <= 0) {
            return back()->with('error', 'Maaf, kuota antrian untuk jadwal ini sudah penuh.');
        }

        // Generate nomor antrian
        $lastAntrian = Pendaftaran::where('dokter_id', $dokter->id)
            ->whereDate('tanggal_daftar', $request->tanggal_daftar)
            ->max('no_antrian');

        $noAntrian = str_pad(($lastAntrian ?? 0) + 1, 3, '0', STR_PAD_LEFT);

        // Simpan pendaftaran
        $pendaftaran = Pendaftaran::create([
            'user_id'       => $user->id,
            'dokter_id'     => $dokter->id,
            'jadwal_id'     => $jadwal->id,
            'tanggal_daftar'=> $request->tanggal_daftar,
            'no_antrian'    => $noAntrian,
            'status'        => 'menunggu',
            'keluhan'       => $request->keluhan,
        ]);

        return redirect()->route('pasien.dashboard')
            ->with('success', "Berhasil mendaftar! Nomor antrian Anda: {$noAntrian}");
    }

    /**
     * Riwayat pendaftaran lengkap
     */
    public function riwayat(Request $request)
    {
        $query = Pendaftaran::with(['dokter', 'jadwal'])
            ->where('user_id', auth()->id())
            ->orderByDesc('tanggal_daftar')
            ->orderByDesc('created_at');

        // Filter status
        if ($request->status && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        $riwayat = $query->paginate(10)->withQueryString();

        return view('pasien.riwayat', compact('riwayat'));
    }

    /**
     * Batalkan antrian
     */
    public function batal(Pendaftaran $pendaftaran)
    {
        // Pastikan antrian milik user yang login
        if ($pendaftaran->user_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak membatalkan antrian ini.');
        }

        // Hanya antrian berstatus "menunggu" yang bisa dibatalkan
        if ($pendaftaran->status !== 'menunggu') {
            return back()->with('error', 'Antrian hanya bisa dibatalkan jika masih berstatus menunggu.');
        }

        $pendaftaran->update(['status' => 'batal']);

        return back()->with('success', 'Antrian berhasil dibatalkan.');
    }
}
