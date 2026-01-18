<?php

namespace App\Exports;

use App\Models\Rawat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Facades\DB;

class KunjunganExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $tanggal_mulai;
    protected $tanggal_selesai;
    protected $idpoli;
    protected $iddokter;
    protected $idjenisrawat;
    protected $cara_datang;

    public function __construct($tanggal_mulai = null, $tanggal_selesai = null, $idpoli = null, $iddokter = null, $idjenisrawat = null, $cara_datang = null)
    {
        $this->tanggal_mulai = $tanggal_mulai;
        $this->tanggal_selesai = $tanggal_selesai;
        $this->idpoli = $idpoli;
        $this->iddokter = $iddokter;
        $this->idjenisrawat = $idjenisrawat;
        $this->cara_datang = $cara_datang;
    }

    public function collection()
    {
        $query = Rawat::with(['pasien', 'poli', 'dokter', 'bayar'])
            ->select('rawat.*');

        if ($this->tanggal_mulai && $this->tanggal_selesai) {
            $query->whereBetween('tglmasuk', [$this->tanggal_mulai . ' 00:00:00', $this->tanggal_selesai . ' 23:59:59']);
        }

        if ($this->idpoli) {
            $query->where('idpoli', $this->idpoli);
        }

        if ($this->iddokter) {
            $query->where('iddokter', $this->iddokter);
        }

        if ($this->idjenisrawat) {
            $query->where('idjenisrawat', $this->idjenisrawat);
        }

        if ($this->cara_datang) {
            $query->where('cara_datang', $this->cara_datang);
        }

        return $query->orderBy('tglmasuk', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal Masuk',
            'No RM',
            'Nama Pasien',
            'Poli',
            'Dokter',
            'Jenis Rawat',
            'Cara Datang',
            'Cara Bayar',
        ];
    }

    public function map($row): array
    {
        $jenisRawat = $row->idjenisrawat == 1 ? 'Rawat Inap' : ($row->idjenisrawat == 2 ? 'Rawat Jalan' : 'UGD');

        return [
            $row->id,
            $row->tglmasuk ? date('d/m/Y H:i', strtotime($row->tglmasuk)) : '-',
            $row->no_rm ?? '-',
            $row->pasien->nama_pasien ?? '-',
            $row->poli->poli ?? '-',
            $row->dokter->nama_dokter ?? '-',
            $jenisRawat,
            $row->cara_datang ?? '-',
            $row->bayar->bayar ?? '-',
        ];
    }

    public function title(): string
    {
        return 'Laporan Kunjungan';
    }
}
