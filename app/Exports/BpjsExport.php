<?php

namespace App\Exports;

use App\Models\Rawat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class BpjsExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $tanggal_mulai;
    protected $tanggal_selesai;

    public function __construct($tanggal_mulai = null, $tanggal_selesai = null)
    {
        $this->tanggal_mulai = $tanggal_mulai;
        $this->tanggal_selesai = $tanggal_selesai;
    }

    public function collection()
    {
        $query = Rawat::with(['pasien', 'poli', 'dokter'])
            ->whereNotNull('no_sep')
            ->where('no_sep', '!=', '')
            ->select('rawat.*');

        if ($this->tanggal_mulai && $this->tanggal_selesai) {
            $query->whereBetween('tglmasuk', [$this->tanggal_mulai . ' 00:00:00', $this->tanggal_selesai . ' 23:59:59']);
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
            'No SEP',
            'No BPJS',
            'Poli',
            'Dokter',
            'Jenis Rawat',
        ];
    }

    public function map($row): array
    {
        $jenisRawat = $row->idjenisrawat == 1 ? 'Rawat Inap' : ($row->idjenisrawat == 2 ? 'Rawat Jalan' : 'UGD');

        return [
            $row->id,
            $row->tglmasuk ? $row->tglmasuk->format('d/m/Y H:i') : '-',
            $row->no_rm ?? '-',
            $row->pasien->nama_pasien ?? '-',
            $row->no_sep ?? '-',
            $row->pasien->no_bpjs ?? '-',
            $row->poli->namapoli ?? '-',
            $row->dokter->namadokter ?? '-',
            $jenisRawat,
        ];
    }

    public function title(): string
    {
        return 'Laporan BPJS';
    }
}
