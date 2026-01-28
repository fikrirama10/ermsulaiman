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
                // Default to current month if no filter, or just today? 
                // User's code originally defaulted to TODAY.
                // Let's broaden to current month to be safe and ensure data shows.
                $start = date('Y-m-01');
                $end = date('Y-m-t');
                $query->whereBetween('tgl_rawat', [$start, $end]);
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
            'idpoli' => 'required', // Poli asal or Poli tujuan
        ]);

        try {
            $pasien = Pasien::where('no_rm', $request->no_rm)->firstOrFail();
            $dokter = Dokter::find($request->iddokter);
            $poli = Poli::find($request->idpoli);

            // Generate No SPRI (Format: SPRI/YYYYMMDD/XXXX)
            $count = RawatSpri::whereDate('tgl_rawat', date('Y-m-d'))->count() + 1;
            $no_spri = 'SPRI/' . date('Ymd') . '/' . str_pad($count, 4, '0', STR_PAD_LEFT);

            $spri = new RawatSpri();
            // Standard Fields
            $spri->no_rm = $request->no_rm;
            $spri->no_spri = $no_spri;
            $spri->tgl_rawat = $request->tgl_rawat;
            $spri->iddokter = $request->iddokter;
            $spri->idpoli = $request->idpoli;
            $spri->iduser = auth()->user()->id;

            // Fixed Values
            $spri->idjenisrawat = 2; // Rawat Inap
            $spri->status = 1; // Created
            $spri->idbayar = $request->idbayar;

            // Updated New Fields (Columns exist: operasi, nama_tindakan, bayi_lahir)
            $spri->operasi = $request->operasi ?? 0;
            $spri->nama_tindakan = $request->nama_tindakan; // Nullable
            $spri->bayi_lahir = $request->bayi_lahir ?? 0;

            // BPJS Integration
            if ($request->idbayar == 2 && $pasien->no_bpjs) {
                // Ensure Doctor and Poli have BPJS codes
                if ($dokter && $dokter->kode_dpjp && $poli && $poli->kode) {
                    $payload = [
                        "request" => [
                            "noKartu" => $pasien->no_bpjs,
                            "kodeDokter" => $dokter->kode_dpjp,
                            "poliKontrol" => $poli->kode,
                            "tglRencanaKontrol" => $request->tgl_rawat,
                            "user" => auth()->user()->name ?? 'Admin'
                        ]
                    ];

                    $bpjsResponse = \App\Helpers\Vclaim\VclaimRencanaKontrolHelper::getInsertSpri($payload);

                    if (isset($bpjsResponse['metaData']) && $bpjsResponse['metaData']['code'] == '200') {
                        // Success
                        $spri->spri_bpjs = $bpjsResponse['response']['noSPRI'];
                        $spri->sep = 0; // Default SEP flag
                    } else {
                        // Log error or flash warning
                        $errMsg = $bpjsResponse['metaData']['message'] ?? ($bpjsResponse['error'] ?? 'Unknown Error');
                        session()->flash('warning', 'Gagal membuat SPRI di BPJS: ' . $errMsg);
                    }
                } else {
                    session()->flash('warning', 'Gagal bridging SPRI: Kode DPJP atau Kode Poli belum set pada master data.');
                }
            }
            $spri->save();

            return redirect()->route('spri.index')->with('success', 'SPRI berhasil dibuat: ' . $no_spri . ($spri->spri_bpjs ? ' (Bridging OK)' : ''));
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
