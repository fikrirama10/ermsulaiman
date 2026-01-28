<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('karyawan')
                ->leftJoin('user_detail', 'karyawan.id', '=', 'user_detail.idkaryawan')
                ->leftJoin('user', 'user_detail.kode_user', '=', 'user.kode_user')
                ->select(
                    'karyawan.*',
                    'user.username',
                    DB::raw('CASE WHEN user.id IS NOT NULL THEN 1 ELSE 0 END as has_user')
                );

            // Filter by status
            if ($request->has('filter_status') && $request->filter_status != '') {
                $query->where('karyawan.status', $request->filter_status);
            }

            // Filter by bagian
            if ($request->has('filter_bagian') && $request->filter_bagian != '') {
                $query->where('karyawan.bagian', $request->filter_bagian);
            }

            // Filter by kategori
            if ($request->has('filter_kategori') && $request->filter_kategori != '') {
                $query->where('karyawan.kategori', $request->filter_kategori);
            }

            $karyawan = $query->get();

            return DataTables::of($karyawan)
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="form-check-input karyawan-checkbox" value="' . $row->id . '">';
                })
                ->addColumn('status_badge', function ($row) {
                    return $row->status == 1 ?
                        '<span class="badge badge-success">Aktif</span>' :
                        '<span class="badge badge-secondary">Nonaktif</span>';
                })
                ->addColumn('user_status', function ($row) {
                    if ($row->has_user) {
                        return '<span class="badge badge-success"><i class="bi bi-check-circle"></i> ' . ($row->username ?? 'Ada User') . '</span>';
                    } else {
                        return '<span class="badge badge-danger"><i class="bi bi-x-circle"></i> Belum Ada User</span>';
                    }
                })
                ->addColumn('actions', function ($row) {
                    $statusBtn = '<button type="button" class="btn btn-sm ' . ($row->status ? 'btn-warning' : 'btn-success') . '" onclick="toggleStatus(' . $row->id . ')" title="' . ($row->status ? 'Nonaktifkan' : 'Aktifkan') . '">
                        <i class="bi bi-power"></i>
                    </button>';

                    $editBtn = '<button type="button" class="btn btn-sm btn-primary" onclick="editKaryawan(' . $row->id . ')" title="Edit">
                        <i class="bi bi-pencil"></i>
                    </button>';

                    return '<div class="d-flex gap-1">' . $statusBtn . $editBtn . '</div>';
                })
                ->rawColumns(['checkbox', 'status_badge', 'user_status', 'actions'])
                ->addIndexColumn()
                ->make(true);
        }

        // Get unique 'bagian' for filter
        $bagian = DB::table('karyawan')->select('bagian')->distinct()->whereNotNull('bagian')->pluck('bagian');

        // Define categories for filter
        $kategori = ['Perawat', 'Bidan', 'Farmasi', 'Laboratorium', 'Radiologi', 'Gizi', 'Fisioterapi', 'Administrasi', 'Umum', 'Lainnya'];

        return view('karyawan.index', compact('bagian', 'kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_karyawan' => 'required',
            'kategori' => 'required',
            'jabatan' => 'required',
            'bagian' => 'required',
            'status' => 'required|in:0,1',
            'username' => 'required|unique:user,username',
            'password' => 'required|min:6',
            'email' => 'nullable|email',
        ]);

        DB::beginTransaction();
        try {
            // Auto Generate NIP
            $lastKaryawan = DB::table('karyawan')->orderBy('id', 'desc')->first();
            $nextId = $lastKaryawan ? $lastKaryawan->id + 1 : 1;
            $nip = 'PEG' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

            // Handle File Upload
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                // Upload to storage/app/public/karyawan
                $path = $request->file('foto')->store('karyawan', 'public');
                $fotoPath = $path; // Store relative path e.g., "karyawan/filename.jpg"
            }

            // Insert karyawan
            $karyawanId = DB::table('karyawan')->insertGetId([
                'nip' => $nip,
                'nama_karyawan' => $request->nama_karyawan,
                'kategori' => $request->kategori,
                'jabatan' => $request->jabatan,
                'bagian' => $request->bagian,
                'status' => $request->status,
                'foto' => $fotoPath,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Generate kode_user
            $kodeUser = 'KRY' . str_pad($karyawanId, 6, '0', STR_PAD_LEFT);

            // Create user account
            DB::table('user')->insert([
                'username' => $request->username,
                'password' => bcrypt($request->password),
                'password_hash' => bcrypt($request->password),
                'email' => $request->email ?? $request->username . '@rsau.com',
                'status' => 10,
                'idpriv' => 8,
                'kode_user' => $kodeUser,
                'full_name' => $request->nama_karyawan,
                'is_active' => '1',
                'created_at' => time(),
                'updated_at' => time()
            ]);

            // Create user detail
            DB::table('user_detail')->insert([
                'kode_user' => $kodeUser,
                'email' => $request->email ?? $request->username . '@rsau.com',
                'nama' => $request->nama_karyawan,
                'nohp' => '-',
                'alamat' => '-',
                'jenis_kelamin' => '-',
                'idkaryawan' => $karyawanId,
                'dokter' => 0
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Karyawan dan user berhasil ditambahkan']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Gagal menambahkan karyawan: ' . $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        $karyawan = DB::table('karyawan')->where('id', $id)->first();
        return response()->json($karyawan);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_karyawan' => 'required',
            'kategori' => 'required',
            'jabatan' => 'required',
            'bagian' => 'required',
            'status' => 'required|in:0,1',
        ]);

        $updateData = [
            'nama_karyawan' => $request->nama_karyawan,
            'kategori' => $request->kategori,
            'jabatan' => $request->jabatan,
            'bagian' => $request->bagian,
            'status' => $request->status,
            'updated_at' => now()
        ];

        // Handle File Upload
        if ($request->hasFile('foto')) {
            // Delete old photo
            $oldKaryawan = DB::table('karyawan')->where('id', $id)->first();
            if ($oldKaryawan && $oldKaryawan->foto) {
                Storage::disk('public')->delete($oldKaryawan->foto);
            }

            // Upload new photo
            $path = $request->file('foto')->store('karyawan', 'public');
            $updateData['foto'] = $path;
        }

        DB::table('karyawan')->where('id', $id)->update($updateData);

        return response()->json(['success' => true, 'message' => 'Data karyawan berhasil diupdate']);
    }

    public function toggleStatus($id)
    {
        $karyawan = DB::table('karyawan')->where('id', $id)->first();
        $newStatus = $karyawan->status == 1 ? 0 : 1;

        DB::table('karyawan')->where('id', $id)->update(['status' => $newStatus, 'updated_at' => now()]);

        return response()->json(['success' => true, 'message' => 'Status karyawan berhasil diubah']);
    }
}
