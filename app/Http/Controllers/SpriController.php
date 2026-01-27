<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\Pasien\Pasien;
use App\Models\Poli;
use App\Models\RawatSpri;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SpriController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = RawatSpri::with(['pasien', 'dokter', 'poli'])
                ->orderBy('id', 'desc');

            if ($request->tgl_awal && $request->tgl_akhir) {
                $query->whereBetween('tgl_rawat', [$request->tgl_awal, $request->tgl_akhir]);
            } else {
                $query->where('tgl_rawat', date('Y-m-d'));
            }

            return DataTables::eloquent($query)
                ->editColumn('tgl_rawat', function ($row) {
                    return $row->tgl_rawat ? Carbon::parse($row->tgl_rawat)->format('d/m/Y') : '-';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('spri.cetak', $row->id) . '" target="_blank" class="btn btn-sm btn-info me-1" title="Cetak SPRI"><i class="fas fa-print"></i></a>';
                    // $btn .= '<a href="'.route('spri.edit', $row->id).'" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('spri.index');
    }

    public function create()
    {
        $poli = Poli::all();
        $dokter = Dokter::where('status', 1)->get();
        return view('spri.create', compact('poli', 'dokter'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_rm' => 'required',
            'tgl_rawat' => 'required|date',
            'iddokter' => 'required',
            'idpoli' => 'required', // Poli asal or Poli tujuan (Rawat Inap usually doesn't have poli, but DPJP does)
        ]);

        try {
            $pasien = Pasien::where('no_rm', $request->no_rm)->firstOrFail();

            // Generate No SPRI (Format: SPRI/YYYYMMDD/XXXX)
            $count = RawatSpri::whereDate('created_at', date('Y-m-d'))->count() + 1;
            $no_spri = 'SPRI/' . date('Ymd') . '/' . str_pad($count, 4, '0', STR_PAD_LEFT);

            $spri = new RawatSpri();
            $spri->no_rm = $request->no_rm;
            $spri->no_spri = $no_spri;
            $spri->tgl_rawat = $request->tgl_rawat;
            $spri->iddokter = $request->iddokter; // DPJP
            $spri->idpoli = $request->idpoli; // Poli Asal (IGD/Poli)
            $spri->iduser = auth()->user()->id;
            $spri->diagnosa = $request->diagnosa; // Assuming we add column or use another way? Table schema didn't have diagnosa column but user req "diagnosa masuk".
            // Checking schema again: no 'diagnosa' column in 'rawat_spri' from user provided schema.
            // Maybe 'nama_tindakan' or just ignored for now?
            // "pemilihan ruangan dan juga bed" - usually this is in Rawat Registration, not SPRI explicitly, but SPRI might have "rencana ruangan".
            // Schema has 'idjenisrawat', 'idbayar'.

            $spri->idjenisrawat = 2; // Rawat Inap
            $spri->status = 1; // Created
            $spri->idbayar = $request->idbayar;
            $spri->created_at = now(); // Use created_at if timestamp enabled, or custom field? Model has timestamps=false.

            $spri->save();

            return redirect()->route('spri.index')->with('success', 'SPRI berhasil dibuat: ' . $no_spri);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat SPRI: ' . $e->getMessage())->withInput();
        }
    }

    public function cetak($id)
    {
        $spri = RawatSpri::with(['pasien', 'dokter', 'poli'])->findOrFail($id);
        return view('spri.cetak', compact('spri'));
    }
}
