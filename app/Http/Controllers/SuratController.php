<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\Pasien\Pasien;
use App\Models\Surat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Setting;

class SuratController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Surat::with(['dokter']);

            // Filter by type
            if ($request->has('filter_jenis') && $request->filter_jenis != '') {
                $query->where('jenis_surat', $request->filter_jenis);
            }

            // Filter by date
            if ($request->has('filter_tanggal') && $request->filter_tanggal != '') {
                $query->whereDate('tanggal_surat', $request->filter_tanggal);
            }

            return DataTables::of($query)
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex gap-1">';
                    $btn .= '<a href="' . route('surat.print', $row->id) . '" target="_blank" class="btn btn-sm btn-info" title="Cetak"><i class="ki-outline ki-printer"></i></a>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger" onclick="deleteSurat(' . $row->id . ')" title="Hapus"><i class="ki-outline ki-trash"></i></button>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->addColumn('dokter_nama', function ($row) {
                    return $row->nama_dokter ?? ($row->dokter->nama_dokter ?? '-');
                })
                ->addColumn('pasien_info', function ($row) {
                    return $row->nama_pasien;
                })
                ->editColumn('jenis_surat', function ($row) {
                    return ucfirst($row->jenis_surat);
                })
                ->editColumn('tanggal_surat', function ($row) {
                    return Carbon::parse($row->tanggal_surat)->format('d/m/Y');
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('surat.index');
    }

    public function create()
    {
        $dokters = Dokter::where('status', 1)->get();
        // Assuming we can search patients via ajax, initial load might be empty or specific logic needed
        // For now just passing doctors
        return view('surat.create', compact('dokters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_surat' => 'required',
            'no_surat' => 'required|unique:surat,no_surat',
            'tanggal_surat' => 'required|date',
            'nama_pasien' => 'required',
            'id_dokter' => 'required',
        ]);

        // Prepare content JSON based on type
        $konten = [];
        switch ($request->jenis_surat) {
            case 'sakit':
                $konten = [
                    'dari_tgl' => $request->sakit_dari_tgl,
                    'sampai_tgl' => $request->sakit_sampai_tgl,
                    'lama_istirahat' => $request->sakit_lama,
                    'diagnosa' => $request->sakit_diagnosa
                ];
                break;
            case 'lahir':
                $konten = [
                    'nama_bayi' => $request->lahir_nama_bayi,
                    'tgl_lahir' => $request->lahir_tgl,
                    'jam_lahir' => $request->lahir_jam,
                    'jenis_kelamin' => $request->lahir_jk,
                    'berat_bb' => $request->lahir_bb,
                    'panjang_pb' => $request->lahir_pb,
                    'nama_ayah' => $request->lahir_ayah,
                    'nama_ibu' => $request->lahir_ibu,
                    'anak_ke' => $request->lahir_anak_ke
                ];
                break;
            case 'kematian':
                $konten = [
                    'waktu_kematian' => $request->mati_waktu,
                    'tempat_kematian' => $request->mati_tempat,
                    'sebab_kematian' => $request->mati_sebab
                ];
                break;
            case 'rujukan':
                $konten = [
                    'faskes_tujuan' => $request->rujuk_faskes,
                    'poli_tujuan' => $request->rujuk_poli,
                    'diagnosa' => $request->rujuk_diagnosa,
                    'tindakan' => $request->rujuk_tindakan,
                    'alasan' => $request->rujuk_alasan
                ];
                break;
            case 'lainnya':
                $konten = [
                    'perihal' => $request->lain_perihal,
                    'isi' => $request->lain_isi
                ];
                break;
        }

        // Get Dokter Name snapshot
        $dokter = Dokter::find($request->id_dokter);

        Surat::create([
            'no_surat' => $request->no_surat,
            'jenis_surat' => $request->jenis_surat,
            'id_pasien' => $request->id_pasien, // Nullable if manual input
            'nama_pasien' => $request->nama_pasien,
            'id_dokter' => $request->id_dokter,
            'nama_dokter' => $dokter ? $dokter->nama_dokter : null,
            'tanggal_surat' => $request->tanggal_surat,
            'konten' => $konten,
            'created_by' => auth()->id()
        ]);

        return redirect()->route('surat.index')->with('success', 'Surat berhasil dibuat');
    }

    public function print($id)
    {
        $surat = Surat::findOrFail($id);

        // Define view based on type
        $view = 'surat.print.default';
        if (view()->exists('surat.print.' . $surat->jenis_surat)) {
            $view = 'surat.print.' . $surat->jenis_surat;
        }

        return view($view, compact('surat'));
    }

    public function destroy($id)
    {
        Surat::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Surat berhasil dihapus']);
    }

    // Ajax helper to generate number
    // Ajax helper to generate number
    public function generateNumber(Request $request)
    {
        $type = $request->type; // sakit, lahir, etc

        // Define specific codes
        $codes = [
            'sakit' => 'SKS',
            'lahir' => 'SKL',
            'kematian' => 'SKM',
            'rujukan' => 'SKR',
            'lainnya' => 'SK',
        ];
        $code = $codes[$type] ?? 'SK';
        $romans = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $monthRoman = $romans[date('n') - 1];
        $monthNum = date('m');
        $year = date('Y');

        // Find the last letter of this type in the current year
        $lastSurat = Surat::where('jenis_surat', $type)
            ->whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        // Default start
        $nextSequence = 1;

        if ($lastSurat) {
            // Try to extract sequence from previous number using regex if format changed, 
            // OR simpler: just assume if we are using custom format, we might need a robust way to track sequence.
            // For now, let's stick to the database count logic if format parsing is too complex, 
            // BUT user asked for "sequential from last", so we rely on the DB records.
            // If the format is strictly {SEQUENCE}/..., we can try to parse.
            // A safer approach for "configurable" formats is often to rely on a separate counter table, 
            // but here we will try to extract the first number found in the string.
            if (preg_match('/(\d+)/', $lastSurat->no_surat, $matches)) {
                $nextSequence = intval($matches[1]) + 1;
            } else {
                // Fallback if regex fails (e.g. no numbers in prev format)
                $nextSequence = Surat::where('jenis_surat', $type)->whereYear('created_at', $year)->count() + 1;
            }
        }

        // Get format from settings
        $format = Setting::getValue('surat_format', '{SEQUENCE}/SURAT-{CODE}/{ROMAN}/{YEAR}');

        $sequencePadded = sprintf("%03d", $nextSequence);

        // Replace Placeholders
        $number = str_replace(
            ['{SEQUENCE}', '{CODE}', '{ROMAN}', '{MONTH}', '{YEAR}'],
            [$sequencePadded, $code, $monthRoman, $monthNum, $year],
            $format
        );

        return response()->json(['number' => $number]);
    }
    public function searchPasien(Request $request)
    {
        $term = $request->term;
        $pasien = Pasien::where('no_rm', 'like', "%$term%")
            ->orWhere('nama_pasien', 'like', "%$term%")
            ->orderBy('nama_pasien', 'asc')
            ->limit(10)
            ->get();

        return response()->json($pasien);
    }
}
