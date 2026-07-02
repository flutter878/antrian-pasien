<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\JadwalPraktek;
use App\Models\Pendaftaran;
use App\Models\User;
use App\Notifications\PanggilanAntrian;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // ─────────────────────────────────────────────
    //  DASHBOARD
    // ─────────────────────────────────────────────

    public function dashboard()
    {
        $totalPasien   = User::where('role', 'pasien')->count();
        $totalDokter   = Dokter::count();
        $totalAntrian  = Pendaftaran::whereDate('tanggal_daftar', today())->count();
        $totalMenunggu = Pendaftaran::whereDate('tanggal_daftar', today())
            ->where('status', 'menunggu')->count();
        $totalSelesai  = Pendaftaran::whereDate('tanggal_daftar', today())
            ->where('status', 'selesai')->count();

        // Antrian hari ini per dokter
        $antrianPerDokter = Dokter::withCount([
            'pendaftaran as total_hari_ini' => fn($q) =>
            $q->whereDate('tanggal_daftar', today()),
            'pendaftaran as menunggu' => fn($q) =>
            $q->whereDate('tanggal_daftar', today())->where('status', 'menunggu'),
            'pendaftaran as selesai' => fn($q) =>
            $q->whereDate('tanggal_daftar', today())->where('status', 'selesai'),
        ])->get();

        // 5 antrian terbaru hari ini
        $antrianTerbaru = Pendaftaran::with(['user', 'dokter'])
            ->whereDate('tanggal_daftar', today())
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPasien',
            'totalDokter',
            'totalAntrian',
            'totalMenunggu',
            'totalSelesai',
            'antrianPerDokter',
            'antrianTerbaru',
        ));
    }

    // ─────────────────────────────────────────────
    //  KELOLA ANTRIAN
    // ─────────────────────────────────────────────

    public function antrian(Request $request)
    {
        $tanggal  = $request->tanggal ?? today()->toDateString();
        $dokterId = $request->dokter_id;
        $status   = $request->status;

        $query = Pendaftaran::with(['user', 'dokter', 'jadwal'])
            ->whereDate('tanggal_daftar', $tanggal);

        if ($dokterId) {
            $query->where('dokter_id', $dokterId);
        }
        if ($status) {
            $query->where('status', $status);
        }

        $antrian = $query->orderBy('no_antrian')->get();
        $dokter  = Dokter::orderBy('nama')->get();

        return view('admin.antrian', compact('antrian', 'dokter', 'tanggal', 'dokterId', 'status'));
    }

    public function updateStatus(Request $request, Pendaftaran $pendaftaran)
    {
        $request->validate([
            'status' => 'required|in:menunggu,dipanggil,selesai,batal',
        ]);

        $old = $pendaftaran->status;
        $pendaftaran->update(['status' => $request->status]);

        // Jika diubah menjadi 'dipanggil', kirim notifikasi ke pasien
        if ($request->status === 'dipanggil' && $old !== 'dipanggil') {
            try {
                $pendaftaran->user->notify(new PanggilanAntrian($pendaftaran));
            } catch (\Throwable $e) {
                // jangan blokir perubahan status bila notifikasi gagal
            }
        }

        return back()->with('success', "Status antrian #{$pendaftaran->no_antrian} berhasil diubah.");
    }

    // ─────────────────────────────────────────────
    //  KELOLA DOKTER
    // ─────────────────────────────────────────────

    public function dokterIndex()
    {
        $dokter = Dokter::withCount('jadwalPraktek')->orderBy('nama')->get();
        return view('admin.dokter.index', compact('dokter'));
    }

    public function dokterCreate()
    {
        return view('admin.dokter.create');
    }

    public function dokterStore(Request $request)
    {
        $data = $request->validate([
            'nama'         => 'required|string|max:100',
            'spesialisasi' => 'required|string|max:100',
            'bio'          => 'nullable|string|max:1000',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('dokter', 'public');
        }

        Dokter::create($data);

        return redirect()->route('admin.dokter.index')
            ->with('success', 'Dokter berhasil ditambahkan.');
    }

    public function dokterEdit(Dokter $dokter)
    {
        return view('admin.dokter.edit', compact('dokter'));
    }

    public function dokterUpdate(Request $request, Dokter $dokter)
    {
        $data = $request->validate([
            'nama'         => 'required|string|max:100',
            'spesialisasi' => 'required|string|max:100',
            'bio'          => 'nullable|string|max:1000',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($dokter->foto) {
                \Storage::disk('public')->delete($dokter->foto);
            }
            $data['foto'] = $request->file('foto')->store('dokter', 'public');
        } else {
            unset($data['foto']);
        }

        $dokter->update($data);

        return redirect()->route('admin.dokter.index')
            ->with('success', 'Data dokter berhasil diperbarui.');
    }

    public function dokterDestroy(Dokter $dokter)
    {
        if ($dokter->foto) {
            \Storage::disk('public')->delete($dokter->foto);
        }

        $dokter->delete();

        return back()->with('success', 'Dokter berhasil dihapus.');
    }

    // ─────────────────────────────────────────────
    //  KELOLA JADWAL PRAKTEK
    // ─────────────────────────────────────────────

    public function jadwalIndex(Request $request)
    {
        $dokterId = $request->dokter_id;

        $query = JadwalPraktek::with('dokter');
        if ($dokterId) {
            $query->where('dokter_id', $dokterId);
        }

        $jadwal = $query->get()->sortBy(function ($j) {
            return [JadwalPraktek::$urutanHari[$j->hari], $j->dokter->nama];
        });

        $dokter = Dokter::orderBy('nama')->get();

        return view('admin.jadwal.index', compact('jadwal', 'dokter', 'dokterId'));
    }

    public function jadwalCreate()
    {
        $dokter = Dokter::orderBy('nama')->get();
        $hariList = array_keys(JadwalPraktek::$urutanHari);
        return view('admin.jadwal.create', compact('dokter', 'hariList'));
    }

    public function jadwalStore(Request $request)
    {
        $request->validate([
            'dokter_id'   => 'required|exists:dokter,id',
            'hari'        => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'kuota'       => 'required|integer|min:1|max:100',
        ]);

        // Cek duplikat dokter + hari
        $exists = JadwalPraktek::where('dokter_id', $request->dokter_id)
            ->where('hari', $request->hari)->exists();

        if ($exists) {
            return back()->withInput()
                ->with('error', 'Dokter ini sudah memiliki jadwal pada hari tersebut.');
        }

        JadwalPraktek::create([
            'dokter_id'   => $request->dokter_id,
            'hari'        => $request->hari,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'kuota'       => $request->kuota,
            'aktif'       => $request->has('aktif'),
        ]);

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal praktek berhasil ditambahkan.');
    }

    public function jadwalEdit(JadwalPraktek $jadwal)
    {
        $dokter   = Dokter::orderBy('nama')->get();
        $hariList = array_keys(JadwalPraktek::$urutanHari);
        return view('admin.jadwal.edit', compact('jadwal', 'dokter', 'hariList'));
    }

    public function jadwalUpdate(Request $request, JadwalPraktek $jadwal)
    {
        $request->validate([
            'dokter_id'   => 'required|exists:dokter,id',
            'hari'        => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'kuota'       => 'required|integer|min:1|max:100',
        ]);

        // Cek duplikat (kecuali diri sendiri)
        $exists = JadwalPraktek::where('dokter_id', $request->dokter_id)
            ->where('hari', $request->hari)
            ->where('id', '!=', $jadwal->id)
            ->exists();

        if ($exists) {
            return back()->withInput()
                ->with('error', 'Dokter ini sudah memiliki jadwal pada hari tersebut.');
        }

        $jadwal->update([
            'dokter_id'   => $request->dokter_id,
            'hari'        => $request->hari,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'kuota'       => $request->kuota,
            'aktif'       => $request->has('aktif'),
        ]);

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal praktek berhasil diperbarui.');
    }

    public function jadwalDestroy(JadwalPraktek $jadwal)
    {
        $jadwal->delete();
        return back()->with('success', 'Jadwal praktek berhasil dihapus.');
    }

    public function jadwalToggle(JadwalPraktek $jadwal)
    {
        $jadwal->update(['aktif' => !$jadwal->aktif]);
        $label = $jadwal->aktif ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Jadwal berhasil {$label}.");
    }
}
