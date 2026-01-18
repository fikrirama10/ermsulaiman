<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ruangan\Ruangan;
use App\Models\Ruangan\RuanganBed;
use App\Models\Ruangan\RuanganKelas;
use App\Models\Ruangan\RuanganJenis;
use App\Models\Ruangan\RuanganGender;
use Yajra\DataTables\Facades\DataTables;

class RuanganController extends Controller
{
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $ruangan = Ruangan::with('kelas', 'bed', 'jenisRuangan', 'genderRuangan');

            // Filter berdasarkan status
            if ($request->has('filter_status') && $request->filter_status !== '') {
                $ruangan->where('status', $request->filter_status);
            }

            return DataTables::of($ruangan)->addColumn('checkbox', function (Ruangan $ruangan) {
                return '<input type="checkbox" class="form-check-input ruangan-checkbox" value="' . $ruangan->id . '">';
            })->addColumn('opsi', function (Ruangan $ruangan) {
                $statusBtn = '<form action="' . route('toggle.ruangan.status', $ruangan->id) . '" method="POST" style="display:inline" class="me-1">' .
                    csrf_field() .
                    '<button type="submit" class="btn btn-sm ' . ($ruangan->status ? 'btn-warning' : 'btn-success') . '" title="' . ($ruangan->status ? 'Nonaktifkan' : 'Aktifkan') . '">' .
                    '<i class="bi bi-power"></i></button></form>';
                $detailBtn = '<a href="' . route('index.ruangan-bed', $ruangan->id) . '" class="btn btn-sm btn-primary" title="Kelola Bed"><i class="bi bi-gear-fill"></i></a>';
                return '<div class="d-flex gap-1">' . $statusBtn . $detailBtn . '</div>';
            })
                ->addColumn('kelas', function (Ruangan $ruangan) {
                    return $ruangan->kelas->kelas;
                })
                ->addColumn('ruangan_jenis', function (Ruangan $ruangan) {
                    return $ruangan->jenisRuangan->ruangan_jenis;
                })
                ->addColumn('gender', function (Ruangan $ruangan) {
                    return $ruangan->genderRuangan->gender;
                })
                ->addColumn('status_aktif', function (Ruangan $ruangan) {
                    return $ruangan->status ? '<span class="badge text-white bg-success">Aktif</span>' : '<span class="badge text-white bg-secondary">Nonaktif</span>';
                })
                ->addColumn('jumlah_bed', function (Ruangan $ruangan) {
                    $total = $ruangan->bed->count();
                    $kosong = $ruangan->bed->where('terisi', 0)->count();
                    return '<span class="badge text-white bg-info">' . $kosong . '/' . $total . '</span>';
                })
                ->rawColumns(['checkbox', 'opsi', 'kelas', 'ruangan_jenis', 'gender', 'status_aktif', 'jumlah_bed'])
                ->addIndexColumn()
                ->make(true);
        }

        $jenis = RuanganJenis::get();
        $gender = RuanganGender::get();
        $kelas = RuanganKelas::get();

        return view('ruangan.index', compact('jenis', 'gender', 'kelas'));
    }

    public function store(Request $request)
    {
        $ruangan = new Ruangan;
        $ruangan->idjenis = $request->jenis_ruangan;
        $ruangan->nama_ruangan = $request->nama;
        $ruangan->kapasitas = 0;
        $ruangan->gender = $request->gender;
        $ruangan->idkelas = $request->kelas;
        $ruangan->status = $request->status;
        $ruangan->jenis = 2;
        $ruangan->keterangan = $request->keterangan;

        $jumlah_kode = Ruangan::where('kode_ruangan', '!=', null)->count();
        $jumlah_kode++;
        $kode_ruangan = 'RG' . str_pad($jumlah_kode, 3, '0', STR_PAD_LEFT);

        $ruangan->kode_ruangan = $kode_ruangan;
        $ruangan->save();

        return redirect()->back()->with('berhasil', 'Data Ruangan Berhasil Di Simpan!');
    }

    // Aktif/Nonaktifkan ruangan
    public function toggleStatus($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        $ruangan->status = $ruangan->status ? 0 : 1;
        $ruangan->save();
        return back();
    }

    // Bulk aktif/nonaktif ruangan
    public function bulkToggleStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'action' => 'required|in:activate,deactivate'
        ]);

        $status = $request->action === 'activate' ? 1 : 0;
        $updated = Ruangan::whereIn('id', $request->ids)->update(['status' => $status]);

        return back()->with('berhasil', "Berhasil mengubah status {$updated} ruangan!");
    }
}
