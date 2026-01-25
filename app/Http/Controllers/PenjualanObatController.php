<?php

namespace App\Http\Controllers;

use App\Models\Rawat;
use App\Models\Obat\Obat;
use Illuminate\Http\Request;
use App\Models\Pasien\Pasien;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Farmasi\PenjualanObat;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Farmasi\PenjualanObatDetail;

class PenjualanObatController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $query = PenjualanObat::with('user')->orderBy('id', 'desc');
            return DataTables::eloquent($query)
                ->addColumn('user', function ($item) {
                    return $item->user->name;
                })
                ->editColumn('tanggal', function ($item) {
                    return date('d-m-Y', strtotime($item->tanggal));
                })
                ->addColumn('aksi', function ($item) {
                    return '
                        <a href="' . route('penjualan-obat.show', $item->id) . '" class="btn btn-sm btn-info">Detail</a>
                        <a href="' . route('penjualan-obat.cetak', $item->id) . '" target="_blank" class="btn btn-sm btn-warning">Cetak</a>
                    ';
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
        return view('penjualan_obat.index');
    }

    public function create()
    {
        return view('penjualan_obat.create');
    }

    public function searchRawat(Request $request)
    {
        $search = $request->search;
        $rawat = Rawat::with(['pasien', 'poli', 'dokter'])
            ->where('status', 1) // Only active visits
            ->whereHas('pasien', function ($q) use ($search) {
                $q->where('nama_pasien', 'like', "%$search%")
                    ->orWhere('no_rm', 'like', "%$search%");
            })
            ->limit(20)
            ->get();

        $results = [];
        foreach ($rawat as $r) {
            $results[] = [
                'id' => $r->id,
                'text' => $r->no_rm . ' - ' . $r->pasien->nama_pasien . ' (' . $r->poli->poli . ')'
            ];
        }

        return response()->json(['results' => $results]);
    }

    public function searchObat(Request $request)
    {
        $search = $request->search;
        $obat = Obat::where('nama_obat', 'like', "%$search%")
            ->limit(20)
            ->get();

        $results = [];
        foreach ($obat as $o) {
            $results[] = [
                'id' => $o->id,
                'text' => $o->nama_obat . ' (Stok: ' . $o->stok . ')',
                'harga' => $o->harga_jual,
                'stok' => $o->stok
            ];
        }
        return response()->json(['results' => $results]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'items' => 'required|array',
            'items.*.id_obat' => 'required|exists:obat,id',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $penjualan = new PenjualanObat();
            $penjualan->tanggal = $request->tanggal;
            $penjualan->nomor_faktur = 'INV-' . date('YmdHis') . '-' . rand(100, 999);
            $penjualan->id_user = auth()->user()->id;

            // Handle Customer Type
            if ($request->jenis_pembeli == 'umum') {
                $penjualan->nama_pembeli = $request->nama_pembeli;
            } else {
                $rawat = Rawat::find($request->id_rawat);
                if ($rawat) {
                    $penjualan->id_rawat = $rawat->id;
                    $penjualan->nama_pembeli = $rawat->pasien->nama_pasien;
                    // Optional: Link to Rawat logic if needed later
                }
            }

            $penjualan->keterangan = $request->keterangan;
            $penjualan->total_harga = 0; // Will update after calculating details
            $penjualan->save();

            $total_harga = 0;

            foreach ($request->items as $item) {
                $obat = Obat::find($item['id_obat']);
                $subtotal = $obat->harga_jual * $item['jumlah'];

                $detail = new PenjualanObatDetail();
                $detail->id_penjualan = $penjualan->id;
                $detail->id_obat = $obat->id;
                $detail->harga = $obat->harga_jual;
                $detail->jumlah = $item['jumlah'];
                $detail->total = $subtotal;
                $detail->save();

                $total_harga += $subtotal;

                // Decrease Stock (Optional, check if needed based on rules)
                // $obat->decrement('stok', $item['jumlah']);
            }

            $penjualan->total_harga = $total_harga;
            $penjualan->save();

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Transaksi berhasil disimpan', 'id' => $penjualan->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $penjualan = PenjualanObat::with(['detail.obat', 'user', 'rawat.pasien', 'rawat.poli'])->findOrFail($id);
        return view('penjualan_obat.show', compact('penjualan'));
    }

    public function cetak($id)
    {
        $penjualan = PenjualanObat::with(['detail.obat', 'user', 'rawat.pasien'])->findOrFail($id);
        // Load PDF view here
        $pdf = \PDF::loadView('penjualan_obat.cetak', compact('penjualan'));
        $pdf->setPaper('a5', 'portrait');
        return $pdf->stream('Faktur-' . $penjualan->nomor_faktur . '.pdf');
    }
}
