<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ruangan\Ruangan;
use App\Models\Ruangan\RuanganBed;
use App\Models\Ruangan\RuanganKelas;
use App\Models\Ruangan\RuanganJenis;
use App\Models\Ruangan\RuanganGender;
use Yajra\DataTables\Facades\DataTables;

class RuanganBedController extends Controller
{
    public function index(Request $request, $id_ruangan)
    {
        $ruangan = Ruangan::with('kelas','bed','jenisRuangan','genderRuangan')->find($id_ruangan);

        if (request()->ajax()) {
            $bed = RuanganBed::where('idruangan', $id_ruangan);

            // Filter berdasarkan status
            if ($request->has('filter_status') && $request->filter_status !== '') {
                $bed->where('status', $request->filter_status);
            }

            // Filter berdasarkan terisi
            if ($request->has('filter_terisi') && $request->filter_terisi !== '') {
                $bed->where('terisi', $request->filter_terisi);
            }

            return DataTables::of($bed)
            ->addColumn('checkbox', function(RuanganBed $bed) {
                return '<input type="checkbox" class="form-check-input bed-checkbox" value="'.$bed->id.'">';
            })
            ->addColumn('opsi', function(RuanganBed $bed) {
                $statusBtn = '<form action="'.route('toggle.bed.status', $bed->id).'" method="POST" style="display:inline" class="me-1">'.
                    csrf_field().
                    '<button type="submit" class="btn btn-sm '.($bed->status ? 'btn-warning' : 'btn-success').'" title="'.($bed->status ? 'Nonaktifkan' : 'Aktifkan').'">'.
                    '<i class="bi bi-power"></i></button></form>';
                $editBtn = '<a onClick="editBed('. $bed->id .')" class="btn btn-sm btn-info" title="Edit"><i class="bi bi-pencil-fill"></i></a>';
                return '<div class="d-flex gap-1">'.$statusBtn.$editBtn.'</div>';
            })

            ->addColumn('status', function(RuanganBed $bed) {
                return ($bed->status == 1) ? '<span class="badge text-white bg-success">Aktif</span>' : '<span class="badge text-white bg-secondary">Nonaktif</span>';
            })
            ->addColumn('terisi', function(RuanganBed $bed) {
                return ($bed->terisi == 1) ? '<span class="badge text-white bg-danger">Terisi</span>' : '<span class="badge text-white bg-success">Kosong</span>';
            })
            ->addColumn('bayi_label', function(RuanganBed $bed) {
                return ($bed->bayi == 1) ? '<span class="badge text-white bg-info">Ya</span>' : '<span class="badge text-white bg-light text-dark">Tidak</span>';
            })
            ->rawColumns(['checkbox','opsi','status','terisi','bayi_label'])
            ->addIndexColumn()
            ->make(true);
        }

        return view('ruangan.bed.index', compact('ruangan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jumlah_bed' => 'required|integer|min:1|max:50'
        ]);

        $jumlah_bed = $request->jumlah_bed ?? 1;
        $beds_created = [];

        for ($i = 0; $i < $jumlah_bed; $i++) {
            $bed = new RuanganBed;
            $bed->idruangan = $request->idruangan;

            $jumlah_kode = RuanganBed::where('kodebed','!=', null)->count();
            $jumlah_kode++;
            $kodebed = 'BED' . str_pad($jumlah_kode, 4, '0', STR_PAD_LEFT);
            $bed->kodebed = $kodebed;

            $bed->status = $request->status;
            $bed->idjenis = $request->idjenis;
            $bed->terisi = 0;
            $bed->bayi = $request->bayi;
            $bed->save();

            $beds_created[] = $kodebed;
        }

        // Update kapasitas ruangan
        $ruangan = Ruangan::find($request->idruangan);
        if ($ruangan) {
            $total_bed = RuanganBed::where('idruangan', $request->idruangan)->count();
            $ruangan->kapasitas = $total_bed;
            $ruangan->save();
        }


        return redirect()->back()->with('berhasil', 'Data BED Berhasil Di Tambahkan! Kode BED: ' . implode(', ', $beds_created));
    }

    public function edit(Request $request)
    {
        $bed = RuanganBed::with('ruangan','jenisRuangan')->where('id', $request->id_bed)->first();

        return response()->json([
            'status' => true,
            'data' => $bed
        ]);
    }

    public function update(Request $request)
    {
        $bed = RuanganBed::find($request->id_bed);
        $bed->bayi = $request->bayi;
        $bed->status = $request->status;
        $bed->save();

        return redirect()->back()->with('berhasil','Data BED Berhasil Di Edit!');
    }

    // Aktif/Nonaktifkan bed
    public function toggleBedStatus($id)
    {
        $bed = RuanganBed::findOrFail($id);
        $bed->status = $bed->status ? 0 : 1;
        $bed->save();

        return redirect()->back()->with('berhasil', 'Status BED berhasil diubah!');
    }

    // Bulk aktif/nonaktif bed
    public function bulkToggleBedStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'action' => 'required|in:activate,deactivate'
        ]);

        $status = $request->action === 'activate' ? 1 : 0;
        $updated = RuanganBed::whereIn('id', $request->ids)->update(['status' => $status]);

        return redirect()->back()->with('berhasil', "Berhasil mengubah status {$updated} bed!");
    }
}
