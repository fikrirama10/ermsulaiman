<?php

namespace App\Exports;

use App\Models\Rawat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class RawatInapExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $tanggal_mulai;
    protected $tanggal_selesai;
    protected $idruangan;
    protected $iddokter;
    protected $cara_keluar;

    public function __construct($tanggal_mulai = null, $tanggal_selesai = null, $idruangan = null, $iddokter = null, $cara_keluar = null)
    {
        $this->tanggal_mulai = $tanggal_mulai;
        $this->tanggal_selesai = $tanggal_selesai;
        $this->idruangan = $idruangan;
        $this->iddokter = $iddokter;
        $this->cara_keluar = $cara_keluar;
    }

    public function collection()
    {
        $query = Rawat::with(['pasien', 'ruangan', 'dokter', 'bayar'])
            ->where('idjenisrawat', 1)
            ->select('rawat.*');

        if ($this->tanggal_mulai && $this->tanggal_selesai) {
            $query->whereBetween('tglmasuk', [$this->tanggal_mulai . ' 00:00:00', $this->tanggal_selesai . ' 23:59:59']);
        }

        if ($this->idruangan) {
            $query->where('idruangan', $this->idruangan);
        }

        if ($this->iddokter) {
            $query->where('iddokter', $this->iddokter);
        }

        if ($this->cara_keluar) {
            $query->where('cara_keluar', $this->cara_keluar);
        }

        return $query->orderBy('tglmasuk', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'No RM',
            'Nama Pasien',
            'Ruangan',
            'Dokter',
            'Tanggal Masuk',
            'Tanggal Pulang',
            'LOS (Hari)',
            'Cara Keluar',
            'Cara Bayar',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->no_rm ?? '-',
            $row->pasien->nama_pasien ?? '-',
            $row->ruangan->namaruangan ?? '-',
            $row->dokter->namadokter ?? '-',
            $row->tglmasuk ? $row->tglmasuk->format('d/m/Y H:i') : '-',
            $row->tglpulang ? $row->tglpulang->format('d/m/Y H:i') : '-',
            $row->los ?? 0,
            $row->cara_keluar ?? '-',
            $row->bayar->namabayar ?? '-',
        ];
    }

    public function title(): string
    {
        return 'Laporan Rawat Inap';
    }
}
