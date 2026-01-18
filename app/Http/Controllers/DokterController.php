<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokter;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DokterController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('dokter')
                ->leftJoin('dokter_spesialis', 'dokter.idspesialis', '=', 'dokter_spesialis.id')
                ->leftJoin('user_detail', 'dokter.id', '=', 'user_detail.iddokter')
                ->leftJoin('user', 'user_detail.kode_user', '=', 'user.kode_user')
                ->select('dokter.*', 'dokter_spesialis.spesialis', 'user.username',
                         DB::raw('CASE WHEN user.id IS NOT NULL THEN 1 ELSE 0 END as has_user'));

            // Filter by status
            if ($request->has('filter_status') && $request->filter_status != '') {
                $query->where('dokter.status', $request->filter_status);
            }

            // Filter by spesialis
            if ($request->has('filter_spesialis') && $request->filter_spesialis != '') {
                $query->where('dokter.idspesialis', $request->filter_spesialis);
            }

            // Filter by user account status
            if ($request->has('filter_user') && $request->filter_user != '') {
                if ($request->filter_user == 'with_user') {
                    $query->whereNotNull('user.id');
                } else if ($request->filter_user == 'without_user') {
                    $query->whereNull('user.id');
                }
            }

            $dokter = $query->get();

            return DataTables::of($dokter)
                ->addColumn('checkbox', function($dokter) {
                    return '<input type="checkbox" class="form-check-input dokter-checkbox" value="'.$dokter->id.'">';
                })
                ->addColumn('status_badge', function($dokter) {
                    return $dokter->status == 1 ?
                        '<span class="badge badge-success">Aktif</span>' :
                        '<span class="badge badge-secondary">Nonaktif</span>';
                })
                ->addColumn('spesialis_name', function($dokter) {
                    return $dokter->spesialis ?? 'Umum';
                })
                ->addColumn('user_status', function($dokter) {
                    if ($dokter->has_user) {
                        return '<span class="badge badge-success"><i class="bi bi-check-circle"></i> ' . ($dokter->username ?? 'Ada User') . '</span>';
                    } else {
                        return '<span class="badge badge-danger"><i class="bi bi-x-circle"></i> Belum Ada User</span>';
                    }
                })
                ->addColumn('actions', function($dokter) {
                    $statusBtn = '<button type="button" class="btn btn-sm '.($dokter->status ? 'btn-warning' : 'btn-success').'" onclick="toggleStatus('.$dokter->id.')" title="'.($dokter->status ? 'Nonaktifkan' : 'Aktifkan').'">
                        <i class="bi bi-power"></i>
                    </button>';

                    $editBtn = '<button type="button" class="btn btn-sm btn-primary" onclick="editDokter('.$dokter->id.')" title="Edit">
                        <i class="bi bi-pencil"></i>
                    </button>';

                    $jadwalBtn = '<a href="'.route('dokter.jadwal', $dokter->id).'" class="btn btn-sm btn-info" title="Jadwal">
                        <i class="bi bi-calendar"></i>
                    </a>';

                    $kuotaBtn = '<a href="'.route('dokter.kuota', $dokter->id).'" class="btn btn-sm btn-success" title="Kuota">
                        <i class="bi bi-people"></i>
                    </a>';

                    return '<div class="d-flex gap-1">'.$statusBtn.$editBtn.$jadwalBtn.$kuotaBtn.'</div>';
                })
                ->rawColumns(['checkbox', 'status_badge', 'user_status', 'actions'])
                ->addIndexColumn()
                ->make(true);
        }

        $spesialis = DB::table('dokter_spesialis')->get();
        return view('dokter.index', compact('spesialis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_dokter' => 'required|unique:dokter,kode_dokter',
            'nama_dokter' => 'required',
            'idspesialis' => 'nullable',
            'sip' => 'nullable',
            'status' => 'required|in:0,1',
            'username' => 'required|unique:user,username',
            'password' => 'required|min:6',
            'email' => 'nullable|email'
        ]);

        DB::beginTransaction();
        try {
            // Insert dokter
            $dokterId = DB::table('dokter')->insertGetId([
                'kode_dokter' => $request->kode_dokter,
                'nama_dokter' => $request->nama_dokter,
                'idspesialis' => $request->idspesialis,
                'sip' => $request->sip,
                'status' => $request->status
            ]);

            // Generate kode_user
            $kodeUser = 'USR' . str_pad($dokterId, 6, '0', STR_PAD_LEFT);

            // Create user account
            DB::table('user')->insert([
                'username' => $request->username,
                'password' => bcrypt($request->password),
                'password_hash' => bcrypt($request->password),
                'email' => $request->email ?? $request->username . '@rsau.com',
                'status' => 10,
                'idpriv' => 7,
                'kode_user' => $kodeUser,
                'full_name' => $request->nama_dokter,
                'is_active' => '1',
                'created_at' => time(),
                'updated_at' => time()
            ]);

            // Create user detail
            DB::table('user_detail')->insert([
                'kode_user' => $kodeUser,
                'email' => $request->email ?? $request->username . '@rsau.com',
                'nama' => $request->nama_dokter,
                'nohp' => '-',
                'alamat' => '-',
                'jenis_kelamin' => '-',
                'iddokter' => $dokterId,
                'dokter' => 1
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Dokter dan user berhasil ditambahkan']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Gagal menambahkan dokter: ' . $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        $dokter = DB::table('dokter')->where('id', $id)->first();
        return response()->json($dokter);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_dokter' => 'required|unique:dokter,kode_dokter,'.$id,
            'nama_dokter' => 'required',
            'idspesialis' => 'nullable',
            'sip' => 'nullable',
            'status' => 'required|in:0,1'
        ]);

        DB::table('dokter')->where('id', $id)->update([
            'kode_dokter' => $request->kode_dokter,
            'nama_dokter' => $request->nama_dokter,
            'idspesialis' => $request->idspesialis,

            'sip' => $request->sip,
            'status' => $request->status
        ]);

        return response()->json(['success' => true, 'message' => 'Dokter berhasil diupdate']);
    }

    public function toggleStatus($id)
    {
        $dokter = DB::table('dokter')->where('id', $id)->first();
        $newStatus = $dokter->status == 1 ? 0 : 1;

        DB::table('dokter')->where('id', $id)->update(['status' => $newStatus]);

        return response()->json(['success' => true, 'message' => 'Status dokter berhasil diubah']);
    }

    public function bulkToggleStatus(Request $request)
    {
        $ids = explode(',', $request->ids);
        $status = $request->action == 'activate' ? 1 : 0;

        DB::table('dokter')->whereIn('id', $ids)->update(['status' => $status]);

        return response()->json(['success' => true, 'message' => 'Status dokter berhasil diubah']);
    }

    // Jadwal Management
    public function jadwal($dokterId, Request $request)
    {
        if ($request->ajax()) {
            $jadwal = DB::table('dokter_jadwal')
                ->leftJoin('poli', 'dokter_jadwal.idpoli', '=', 'poli.id')
                ->where('dokter_jadwal.iddokter', $dokterId)
                ->select('dokter_jadwal.*', 'poli.poli as nama_poli')
                ->get();

            return DataTables::of($jadwal)
                ->addColumn('hari_nama', function($jadwal) {
                    $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                    return $hari[$jadwal->idhari - 1] ?? '-';
                })
                ->addColumn('waktu', function($jadwal) {
                    return $jadwal->jam_mulai . ' - ' . $jadwal->jam_selesai;
                })
                ->addColumn('status_badge', function($jadwal) {
                    return $jadwal->status == 1 ?
                        '<span class="badge badge-success">Aktif</span>' :
                        '<span class="badge badge-secondary">Nonaktif</span>';
                })
                ->addColumn('actions', function($jadwal) {
                    return '<div class="d-flex gap-1">
                        <button type="button" class="btn btn-sm btn-primary" onclick="editJadwal('.$jadwal->id.')">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteJadwal('.$jadwal->id.')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>';
                })
                ->rawColumns(['status_badge', 'actions'])
                ->addIndexColumn()
                ->make(true);
        }

        $dokter = DB::table('dokter')->where('id', $dokterId)->first();
        $poli = DB::table('poli')->get();

        return view('dokter.jadwal', compact('dokter', 'poli'));
    }

    public function storeJadwal(Request $request)
    {
        $request->validate([
            'iddokter' => 'required',
            'hari' => 'required|in:1,2,3,4,5,6,7',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'idpoli' => 'required',
            'kuota' => 'required|integer|min:1|max:100'
        ]);

        // Cek apakah sudah ada jadwal untuk hari ini
        $existingJadwal = DB::table('dokter_jadwal')
            ->where('iddokter', $request->iddokter)
            ->where('idhari', $request->hari)
            ->first();

        if ($existingJadwal) {
            // Jika sudah ada, tambahkan kuota
            DB::table('dokter_jadwal')
                ->where('id', $existingJadwal->id)
                ->update([
                    'kuota' => DB::raw('kuota + ' . $request->kuota),
                    'jam_mulai' => $request->jam_mulai,
                    'jam_selesai' => $request->jam_selesai,
                    'idpoli' => $request->idpoli
                ]);
            return response()->json(['success' => true, 'message' => 'Kuota jadwal berhasil ditambahkan']);
        } else {
            // Jika belum ada, buat jadwal baru
            DB::table('dokter_jadwal')->insert([
                'iddokter' => $request->iddokter,
                'idhari' => $request->hari,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'idpoli' => $request->idpoli,
                'kuota' => $request->kuota,
                'status' => 1
            ]);
            return response()->json(['success' => true, 'message' => 'Jadwal berhasil ditambahkan']);
        }
    }

    public function editJadwal($id)
    {
        $jadwal = DB::table('dokter_jadwal')->where('id', $id)->first();
        return response()->json($jadwal);
    }

    public function updateJadwal(Request $request, $id)
    {
        $request->validate([
            'iddokter' => 'required',
            'hari' => 'required|in:1,2,3,4,5,6,7',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'idpoli' => 'required',
            'kuota' => 'required|integer|min:1|max:100'
        ]);

        DB::table('dokter_jadwal')->where('id', $id)->update([
            'idhari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'idpoli' => $request->idpoli,
            'kuota' => $request->kuota
        ]);

        return response()->json(['success' => true, 'message' => 'Jadwal berhasil diupdate']);
    }

    public function destroyJadwal($id)
    {
        DB::table('dokter_jadwal')->where('id', $id)->delete();
        return response()->json(['success' => true, 'message' => 'Jadwal berhasil dihapus']);
    }

    // Kuota Management
    public function kuota($dokterId, Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('dokter_kuota')
                ->leftJoin('poli', 'dokter_kuota.idpoli', '=', 'poli.id')
                ->leftJoin('hari', 'dokter_kuota.idhari', '=', 'hari.id')
                ->where('dokter_kuota.iddokter', $dokterId)
                ->where(function($query) {
                    $query->where(DB::raw('YEAR(dokter_kuota.tgl)'), '>', date('Y'))
                          ->orWhere(function($q) {
                              $q->where(DB::raw('YEAR(dokter_kuota.tgl)'), '=', date('Y'))
                                ->where(DB::raw('MONTH(dokter_kuota.tgl)'), '>=', date('m'));
                          });
                })
                ->select('dokter_kuota.*', 'poli.poli as nama_poli', 'hari.hari as nama_hari')->orderby('dokter_kuota.tgl','desc');

            // Filter by tanggal
            if ($request->has('tanggal') && $request->tanggal != '') {
                $query->where('dokter_kuota.tgl', $request->tanggal);
            }

            // Filter by status
            if ($request->has('status') && $request->status != '') {
                $query->where('dokter_kuota.status', $request->status);
            }

            $kuota = $query->get();

            return DataTables::of($kuota)
                ->addColumn('tanggal_formatted', function($kuota) {
                    return $kuota->tgl ? date('d/m/Y', strtotime($kuota->tgl)) : '-';
                })
                ->addColumn('hari_nama', function($kuota) {
                    return $kuota->nama_hari ?? '-';
                })
                ->addColumn('kuota', function($kuota) {
                    $used = $kuota->terdaftar ?? 0;
                    $total = $kuota->kuota;
                    return $used.'/'.$total;
                })
                ->addColumn('progress', function($kuota) {
                    $used = $kuota->terdaftar ?? 0;
                    $total = $kuota->kuota;
                    $remaining = $total - $used;
                    $percentage = $total > 0 ? round(($used / $total) * 100) : 0;

                    return '<div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="progress h-20px">
                                <div class="progress-bar bg-'.($percentage > 80 ? 'danger' : ($percentage > 60 ? 'warning' : 'success')).'"
                                     role="progressbar"
                                     style="width: '.$percentage.'%;"
                                     aria-valuenow="'.$used.'"
                                     aria-valuemin="0"
                                     aria-valuemax="'.$total.'">
                                    '.$used.'/'.$total.'
                                </div>
                            </div>
                        </div>
                    </div>';
                })
                ->addColumn('status_badge', function($kuota) {
                    return $kuota->status == 1 ?
                        '<span class="badge badge-success">Aktif</span>' :
                        '<span class="badge badge-secondary">Nonaktif</span>';
                })
                ->addColumn('actions', function($kuota) {
                    return '<div class="d-flex gap-1">
                        <button type="button" class="btn btn-sm btn-primary" onclick="editKuota('.$kuota->id.')">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteKuota('.$kuota->id.')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>';
                })
                ->rawColumns(['progress', 'status_badge', 'actions'])
                ->addIndexColumn()
                ->make(true);
        }

        $dokter = DB::table('dokter')->where('id', $dokterId)->first();
        $poli = DB::table('poli')->get();

        return view('dokter.kuota', compact('dokter', 'poli'));
    }

    public function storeKuota(Request $request)
    {
        $request->validate([
            'iddokter' => 'required',
            'hari' => 'required|in:1,2,3,4,5,6,7',
            'kuota_total' => 'required|integer|min:1',
            'idpoli' => 'required'
        ]);

        DB::table('dokter_kuota')->insert([
            'iddokter' => $request->iddokter,
            'hari' => $request->hari,
            'kuota_total' => $request->kuota_total,
            'kuota_terpakai' => 0,
            'idpoli' => $request->idpoli
        ]);

        return response()->json(['success' => true, 'message' => 'Kuota berhasil ditambahkan']);
    }

    public function kuotaSummary($dokterId)
    {
        $summary = DB::table('dokter_kuota')
            ->where('iddokter', $dokterId)
            ->select(
                DB::raw('SUM(kuota) as total_kuota'),
                DB::raw('SUM(terdaftar) as kuota_terpakai'),
                DB::raw('SUM(sisa) as kuota_sisa')
            )
            ->first();

        return response()->json([
            'total_kuota' => $summary->total_kuota ?? 0,
            'kuota_terpakai' => $summary->kuota_terpakai ?? 0,
            'kuota_sisa' => $summary->kuota_sisa ?? 0
        ]);
    }

    // Sync User Methods
    public function getDokterWithoutUser()
    {
        $dokter = DB::table('dokter')
            ->leftJoin('user_detail', 'dokter.id', '=', 'user_detail.iddokter')
            ->leftJoin('user', 'user_detail.kode_user', '=', 'user.kode_user')
            ->whereNull('user.id')
            ->select('dokter.*')
            ->get();

        return response()->json($dokter);
    }

    public function syncUser(Request $request)
    {
        $dokterId = $request->dokter_id;
        $dokter = DB::table('dokter')->where('id', $dokterId)->first();

        if (!$dokter) {
            return response()->json(['success' => false, 'message' => 'Dokter tidak ditemukan'], 404);
        }

        // Cek apakah sudah punya user
        $hasUser = DB::table('user_detail')
            ->join('user', 'user_detail.kode_user', '=', 'user.kode_user')
            ->where('user_detail.iddokter', $dokterId)
            ->exists();

        if ($hasUser) {
            return response()->json(['success' => false, 'message' => 'Dokter sudah memiliki akun user'], 400);
        }

        DB::beginTransaction();
        try {
            // Generate kode_user
            $kodeUser = 'USR' . str_pad($dokterId, 6, '0', STR_PAD_LEFT);

            // Generate username dari nama dokter
            $username = strtolower(str_replace(' ', '', $dokter->nama_dokter));
            $usernameBase = $username;
            $counter = 1;

            // Cek username unik
            while (DB::table('user')->where('username', $username)->exists()) {
                $username = $usernameBase . $counter;
                $counter++;
            }

            // Default password: dokter123
            $defaultPassword = 'dokter123';

            // Create user account
            DB::table('user')->insert([
                'username' => $username,
                'password' => bcrypt($defaultPassword),
                'password_hash' => bcrypt($defaultPassword),
                'email' => $dokter->kode_dokter . '@rsau.com',
                'status' => 10,
                'idpriv' => 7,
                'kode_user' => $kodeUser,
                'full_name' => $dokter->nama_dokter,
                'is_active' => '1',
                'created_at' => time(),
                'updated_at' => time()
            ]);

            // Create user detail
            DB::table('user_detail')->insert([
                'kode_user' => $kodeUser,
                'email' => $dokter->kode_dokter . '@rsau.com',
                'nama' => $dokter->nama_dokter,
                'nohp' => '-',
                'alamat' => '-',
                'jenis_kelamin' => '-',
                'iddokter' => $dokterId,
                'dokter' => 1
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'User berhasil dibuat',
                'username' => $username,
                'password' => $defaultPassword
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Gagal membuat user: ' . $e->getMessage()], 500);
        }
    }

    public function syncAllUsers()
    {
        $dokterWithoutUser = DB::table('dokter')
            ->leftJoin('user_detail', 'dokter.id', '=', 'user_detail.iddokter')
            ->leftJoin('user', 'user_detail.kode_user', '=', 'user.kode_user')
            ->whereNull('user.id')
            ->select('dokter.*')
            ->get();

        if ($dokterWithoutUser->count() == 0) {
            return response()->json(['success' => false, 'message' => 'Semua dokter sudah memiliki akun user'], 400);
        }

        DB::beginTransaction();
        try {
            $created = 0;
            $failed = [];

            foreach ($dokterWithoutUser as $dokter) {
                try {
                    $kodeUser = 'USR' . str_pad($dokter->id, 6, '0', STR_PAD_LEFT);

                    // Generate username
                    $username = strtolower(str_replace(' ', '', $dokter->nama_dokter));
                    $usernameBase = $username;
                    $counter = 1;

                    while (DB::table('user')->where('username', $username)->exists()) {
                        $username = $usernameBase . $counter;
                        $counter++;
                    }

                    $defaultPassword = 'dokter123';

                    DB::table('user')->insert([
                        'username' => $username,
                        'password' => bcrypt($defaultPassword),
                        'password_hash' => bcrypt($defaultPassword),
                        'email' => $dokter->kode_dokter . '@rsau.com',
                        'status' => 10,
                        'idpriv' => 7,
                        'kode_user' => $kodeUser,
                        'full_name' => $dokter->nama_dokter,
                        'is_active' => '1',
                        'created_at' => time(),
                        'updated_at' => time()
                    ]);

                    DB::table('user_detail')->insert([
                        'kode_user' => $kodeUser,
                        'email' => $dokter->kode_dokter . '@rsau.com',
                        'nama' => $dokter->nama_dokter,
                        'nohp' => '-',
                        'alamat' => '-',
                        'jenis_kelamin' => '-',
                        'iddokter' => $dokter->id,
                        'dokter' => 1
                    ]);

                    $created++;
                } catch (\Exception $e) {
                    $failed[] = $dokter->nama_dokter . ': ' . $e->getMessage();
                }
            }

            DB::commit();

            $message = "Berhasil membuat {$created} akun user";
            if (count($failed) > 0) {
                $message .= ". Gagal: " . implode(', ', $failed);
            }

            return response()->json(['success' => true, 'message' => $message, 'created' => $created, 'failed' => count($failed)]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Gagal membuat user: ' . $e->getMessage()], 500);
        }
    }
}
