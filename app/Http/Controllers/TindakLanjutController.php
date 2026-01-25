<?php

namespace App\Http\Controllers;

use App\Helpers\VclaimHelper;
use App\Models\Dokter;
use App\Models\Pasien\Pasien;
use App\Models\Poli;
use App\Models\Rawat;
use App\Models\TindakLanjut;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TindakLanjutController extends Controller
{
    // Konstanta untuk tipe tindak lanjut
    const TIPE_KONTROL = 'Kontrol Kembali';
    const TIPE_RUJUK = 'Dirujuk';
    const TIPE_RAWAT = 'Dirawat';
    const TIPE_INTERM = 'Interm';
    const TIPE_PRB = 'Prb';
    const TIPE_MENINGGAL = 'Meninggal';

    /**
     * Display tindak lanjut form
     */
    public function index(Request $request)
    {
        try {
            $rawat = Rawat::findOrFail($request->idrawat);
            $pasien = Pasien::with('alamat')->where('no_rm', $rawat->no_rm)->firstOrFail();
            $poli = Poli::where('ket', 1)->orderBy('poli')->get();
            $dokter = Dokter::whereNotNull('idspesialis')->orderBy('nama_dokter')->get();

            return view('tindak-lanjut.index', compact('pasien', 'rawat', 'poli', 'dokter'));
        } catch (\Exception $e) {
            Log::error('Error loading tindak lanjut form: ' . $e->getMessage());
            return redirect()->back()->with('gagal', 'Terjadi kesalahan saat memuat form tindak lanjut');
        }
    }

    /**
     * Store new tindak lanjut
     */
    public function post_tindak_lanjut(Request $request, $id)
    {
        // Validasi input
        $validator = $this->validateTindakLanjut($request);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('gagal', 'Validasi gagal. Periksa kembali inputan Anda.');
        }

        DB::beginTransaction();
        try {
            $rawat = Rawat::findOrFail($id);

            // Check if tindak lanjut already exists
            $existing = TindakLanjut::where('idrawat', $id)->first();
            if ($existing) {
                return redirect()->back()->with('gagal', 'Tindak lanjut untuk rawat jalan ini sudah ada. Silakan edit data yang ada.');
            }

            $tindak_lanjut = new TindakLanjut();
            $tindak_lanjut->idrawat = $id;
            $tindak_lanjut->idrekapmedis = $request->idrekapmedis;
            $tindak_lanjut->tindak_lanjut = $request->rencana_tindak_lanjut;
            $tindak_lanjut->catatan = $request->catatan;

            // Set data berdasarkan tipe tindak lanjut
            $this->setTindakLanjutData($tindak_lanjut, $request, $rawat);

            $tindak_lanjut->nomor = $tindak_lanjut->generateNomorOtomatis();
            $tindak_lanjut->save();

            // Handle BPJS integration if needed
            if ($rawat->idbayar == 2) {
                $this->handleBpjsIntegration($tindak_lanjut, $request, $rawat);
            }

            DB::commit();
            return redirect(route('rekam-medis-poli', $id))
                ->with('berhasil', 'Data tindak lanjut berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving tindak lanjut: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('gagal', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Show edit form for tindak lanjut
     */
    public function edit_tindak_lanjut($id)
    {
        try {
            $tindak_lanjut = TindakLanjut::findOrFail($id);
            $rawat = Rawat::findOrFail($tindak_lanjut->idrawat);
            $pasien = Pasien::with('alamat')->where('no_rm', $rawat->no_rm)->firstOrFail();
            $poli_interm = Poli::where('ket', 1)->orderBy('poli')->get();
            $dokter = Dokter::whereNotNull('idspesialis')->orderBy('nama_dokter')->get();

            $poli = [];
            if ($tindak_lanjut->tujuan_tindak_lanjut) {
                $kode_faskes = explode('-', $tindak_lanjut->tujuan_tindak_lanjut);
                if (isset($kode_faskes[0])) {
                    $poli = VclaimHelper::getFaskesSpesialistik($kode_faskes[0], $tindak_lanjut->tgl_tindak_lanjut);
                }
            }

            return view('tindak-lanjut.edit', compact('pasien', 'rawat', 'poli', 'tindak_lanjut', 'dokter', 'poli_interm'));
        } catch (\Exception $e) {
            Log::error('Error loading edit tindak lanjut: ' . $e->getMessage());
            return redirect()->back()->with('gagal', 'Terjadi kesalahan saat memuat form edit');
        }
    }

    /**
     * Update tindak lanjut
     */
    public function post_edit_tindak_lanjut(Request $request, $id)
    {
        // Validasi input
        $validator = $this->validateTindakLanjut($request);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('gagal', 'Validasi gagal. Periksa kembali inputan Anda.');
        }

        DB::beginTransaction();
        try {
            $tindak_lanjut = TindakLanjut::findOrFail($request->id);
            $rawat = Rawat::findOrFail($tindak_lanjut->idrawat);

            $tindak_lanjut->tindak_lanjut = $request->rencana_tindak_lanjut;
            $tindak_lanjut->catatan = $request->catatan;

            // Set data berdasarkan tipe tindak lanjut
            $this->setTindakLanjutData($tindak_lanjut, $request, $rawat);

            $tindak_lanjut->save();

            // Handle BPJS integration if needed
            if ($rawat->idbayar == 2) {
                $this->handleBpjsIntegration($tindak_lanjut, $request, $rawat);
            }

            DB::commit();
            return redirect(route('rekam-medis-poli', $rawat->id))
                ->with('berhasil', 'Data tindak lanjut berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating tindak lanjut: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('gagal', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Delete tindak lanjut
     */
    public function hapus_tindak_lanjut(Request $request)
    {
        DB::beginTransaction();
        try {
            $tindak_lanjut = TindakLanjut::findOrFail($request->id);
            $rawat = Rawat::findOrFail($tindak_lanjut->idrawat);

            $tindak_lanjut->delete();

            DB::commit();
            return redirect(route('rekam-medis-poli', $rawat->id))
                ->with('berhasil', 'Data tindak lanjut berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting tindak lanjut: ' . $e->getMessage());
            return redirect()->back()
                ->with('gagal', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Get partial view based on tindak lanjut type
     */
    public function aksi_tindak_lanjut($id)
    {
        try {
            $poli = Poli::where('ket', 1)->orderBy('poli')->get();
            $dokter = Dokter::whereNotNull('idspesialis')->orderBy('nama_dokter')->get();

            $views = [
                self::TIPE_KONTROL => 'tindak-lanjut.partial.kontrol',
                self::TIPE_RUJUK => 'tindak-lanjut.partial.rujuk',
                self::TIPE_RAWAT => 'tindak-lanjut.partial.rawat',
                self::TIPE_INTERM => 'tindak-lanjut.partial.interm',
                self::TIPE_PRB => 'tindak-lanjut.partial.prb',
                self::TIPE_MENINGGAL => 'tindak-lanjut.partial.meninggal',
            ];

            if (isset($views[$id])) {
                return view($views[$id], compact('poli', 'dokter'));
            }

            return response()->json(['error' => 'Tipe tindak lanjut tidak valid'], 400);
        } catch (\Exception $e) {
            Log::error('Error loading partial view: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan'], 500);
        }
    }

    /**
     * Validate tindak lanjut request
     */
    private function validateTindakLanjut(Request $request)
    {
        $rules = [
            'rencana_tindak_lanjut' => 'required|in:' . implode(',', [
                self::TIPE_KONTROL,
                self::TIPE_RUJUK,
                self::TIPE_RAWAT,
                self::TIPE_INTERM,
                self::TIPE_PRB,
                self::TIPE_MENINGGAL
            ]),
            'catatan' => 'nullable|string|max:1000',
        ];

        // Validasi kondisional berdasarkan tipe
        switch ($request->rencana_tindak_lanjut) {
            case self::TIPE_KONTROL:
                $rules['tgl_kontrol'] = 'required|date|after_or_equal:today';
                break;

            case self::TIPE_RUJUK:
                $rules['poli_rujuk'] = 'required';
                $rules['tujuan_rujuk'] = 'required';
                $rules['tgl_kontrol_rujuk'] = 'required|date|after_or_equal:today';
                break;

            case self::TIPE_RAWAT:
                $rules['tgl_rawat'] = 'required|date|after_or_equal:today';
                $rules['iddokter'] = 'required|exists:demo_dokter,id';
                $rules['operasi'] = 'nullable|in:0,1';
                if ($request->operasi == '1') {
                    $rules['value_operasi'] = 'required|string|max:500';
                }
                break;

            case self::TIPE_INTERM:
                $rules['poli_rujuk'] = 'required';
                $rules['tgl_kontrol_intem'] = 'required|date|after_or_equal:today';
                break;

            case self::TIPE_PRB:
                $rules['tgl_kontrol'] = 'required|date|after_or_equal:today';
                $rules['alasan'] = 'required|string|max:500';
                $rules['rencana_selanjutnya'] = 'required|string|max:500';
                break;

            case self::TIPE_MENINGGAL:
                $rules['tgl_kontrol'] = 'required|date';
                break;
        }

        return Validator::make($request->all(), $rules, [
            'rencana_tindak_lanjut.required' => 'Rencana tindak lanjut harus dipilih',
            'tgl_kontrol.after_or_equal' => 'Tanggal kontrol tidak boleh kurang dari hari ini',
            'tgl_rawat.after_or_equal' => 'Tanggal rawat tidak boleh kurang dari hari ini',
            'iddokter.required' => 'Dokter harus dipilih untuk rawat inap',
            'iddokter.exists' => 'Dokter yang dipilih tidak valid',
        ]);
    }

    /**
     * Set tindak lanjut data based on type
     */
    private function setTindakLanjutData(TindakLanjut &$tindak_lanjut, Request $request, Rawat $rawat)
    {
        // Reset all optional fields
        $tindak_lanjut->poli_rujuk = null;
        $tindak_lanjut->tujuan_tindak_lanjut = null;
        $tindak_lanjut->operasi = null;
        $tindak_lanjut->tindakan_operasi = null;
        $tindak_lanjut->iddokter = null;
        $tindak_lanjut->prb = null;

        switch ($request->rencana_tindak_lanjut) {
            case self::TIPE_KONTROL:
                $tindak_lanjut->poli_rujuk = $rawat->poli->kode;
                $tindak_lanjut->tgl_tindak_lanjut = $request->tgl_kontrol;
                break;

            case self::TIPE_RUJUK:
                $tindak_lanjut->poli_rujuk = $request->poli_rujuk;
                $tindak_lanjut->tujuan_tindak_lanjut = $request->tujuan_rujuk;
                $tindak_lanjut->tgl_tindak_lanjut = $request->tgl_kontrol_rujuk;
                break;

            case self::TIPE_RAWAT:
                $tindak_lanjut->tgl_tindak_lanjut = $request->tgl_rawat;
                $tindak_lanjut->operasi = $request->operasi ? 1 : 0;
                $tindak_lanjut->tindakan_operasi = $request->operasi ? $request->value_operasi : null;
                $tindak_lanjut->iddokter = $request->iddokter;
                break;

            case self::TIPE_INTERM:
                $tindak_lanjut->poli_rujuk = $request->poli_rujuk;
                $tindak_lanjut->tgl_tindak_lanjut = $request->tgl_kontrol_intem;
                $tindak_lanjut->tujuan_tindak_lanjut = "Rujuk Interm";
                break;

            case self::TIPE_PRB:
                $prb = new Collection([
                    'alasan' => $request->alasan,
                    'rencana_selanjutnya' => $request->rencana_selanjutnya,
                ]);
                $tindak_lanjut->poli_rujuk = $rawat->poli->kode;
                $tindak_lanjut->tgl_tindak_lanjut = $request->tgl_kontrol;
                $tindak_lanjut->prb = $prb->toJson();
                $tindak_lanjut->tujuan_tindak_lanjut = "Prb";
                break;

            case self::TIPE_MENINGGAL:
                $tindak_lanjut->tgl_tindak_lanjut = $request->tgl_kontrol;
                break;
        }
    }

    /**
     * Handle BPJS integration (placeholder for future implementation)
     */
    private function handleBpjsIntegration(TindakLanjut $tindak_lanjut, Request $request, Rawat $rawat)
    {
        try {
            switch ($request->rencana_tindak_lanjut) {
                case self::TIPE_KONTROL:
                    // TODO: Insert SKPD
                    Log::info('BPJS - Insert SKPD for tindak lanjut: ' . $tindak_lanjut->id);
                    break;

                case self::TIPE_RUJUK:
                    // TODO: Insert rujukan
                    Log::info('BPJS - Insert rujukan for tindak lanjut: ' . $tindak_lanjut->id);
                    break;

                case self::TIPE_RAWAT:
                    // TODO: Insert SPRI rawat inap
                    Log::info('BPJS - Insert SPRI for tindak lanjut: ' . $tindak_lanjut->id);
                    break;

                case self::TIPE_INTERM:
                    // TODO: Insert SKPD interm
                    Log::info('BPJS - Insert SKPD interm for tindak lanjut: ' . $tindak_lanjut->id);
                    break;

                case self::TIPE_PRB:
                    // TODO: Insert PRB
                    Log::info('BPJS - Insert PRB for tindak lanjut: ' . $tindak_lanjut->id);
                    break;
            }
        } catch (\Exception $e) {
            Log::error('Error in BPJS integration: ' . $e->getMessage());
            // Don't throw exception, just log it
        }
    }
}
