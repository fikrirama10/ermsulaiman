<?php

namespace App\Exports;

use App\Models\Pasien\Pasien;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class DemografiExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $tanggal_mulai;
    protected $tanggal_selesai;
    protected $jenis_kelamin;
    protected $usia_dari;
    protected $usia_sampai;
    protected $kategori_usia;
    protected $idagama;
    protected $idkelurahan;

    public function __construct($tanggal_mulai = null, $tanggal_selesai = null, $jenis_kelamin = null, $usia_dari = null, $usia_sampai = null, $kategori_usia = null, $idagama = null, $idkelurahan = null)
    {
        $this->tanggal_mulai = $tanggal_mulai;
        $this->tanggal_selesai = $tanggal_selesai;
        $this->jenis_kelamin = $jenis_kelamin;
        $this->usia_dari = $usia_dari;
        $this->usia_sampai = $usia_sampai;
        $this->kategori_usia = $kategori_usia;
        $this->idagama = $idagama;
        $this->idkelurahan = $idkelurahan;
    }

    public function collection()
    {
        $query = Pasien::with(['agama']);

        if ($this->tanggal_mulai && $this->tanggal_selesai) {
            $query->whereBetween('tgldaftar', [$this->tanggal_mulai, $this->tanggal_selesai]);
        }

        if ($this->jenis_kelamin) {
            $query->where('jenis_kelamin', $this->jenis_kelamin);
        }

        if ($this->usia_dari && $this->usia_sampai) {
            $query->whereBetween('usia_tahun', [$this->usia_dari, $this->usia_sampai]);
        }

        if ($this->kategori_usia) {
            $kategoriUsia = $this->kategori_usia;
            $query->where(function($q) use ($kategoriUsia) {
                if ($kategoriUsia == 'balita') {
                    $q->where('usia_tahun', '<=', 5);
                } elseif ($kategoriUsia == 'anak') {
                    $q->whereBetween('usia_tahun', [6, 12]);
                } elseif ($kategoriUsia == 'remaja') {
                    $q->whereBetween('usia_tahun', [13, 18]);
                } elseif ($kategoriUsia == 'dewasa') {
                    $q->whereBetween('usia_tahun', [19, 60]);
                } elseif ($kategoriUsia == 'lansia') {
                    $q->where('usia_tahun', '>', 60);
                }
            });
        }

        if ($this->idagama) {
            $query->where('idagama', $this->idagama);
        }

        if ($this->idkelurahan) {
            $query->where('idkelurahan', $this->idkelurahan);
        }

        return $query->orderBy('tgldaftar', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'No RM',
            'Nama Pasien',
            'NIK',
            'Jenis Kelamin',
            'Tanggal Lahir',
            'Usia',
            'Kategori Usia',
            'Agama',
            'Kelurahan',
            'No HP',
            'Tanggal Daftar',
        ];
    }

    public function map($row): array
    {
        $jk = $row->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
        $usia = $row->usia_tahun ? $row->usia_tahun . ' Tahun' : '-';

        // Menentukan kategori usia
        $kategoriUsia = '-';
        if ($row->usia_tahun) {
            if ($row->usia_tahun <= 5) {
                $kategoriUsia = 'Balita (0-5)';
            } elseif ($row->usia_tahun >= 6 && $row->usia_tahun <= 12) {
                $kategoriUsia = 'Anak (6-12)';
            } elseif ($row->usia_tahun >= 13 && $row->usia_tahun <= 18) {
                $kategoriUsia = 'Remaja (13-18)';
            } elseif ($row->usia_tahun >= 19 && $row->usia_tahun <= 60) {
                $kategoriUsia = 'Dewasa (19-60)';
            } else {
                $kategoriUsia = 'Lansia (>60)';
            }
        }

        $agama = $row->agama ? $row->agama->agama : '-';

        return [
            $row->id,
            $row->no_rm ?? '-',
            $row->nama_pasien ?? '-',
            $row->nik ?? '-',
            $jk,
            $row->tgllahir ? date('d/m/Y', strtotime($row->tgllahir)) : '-',
            $usia,
            $kategoriUsia,
            $agama,
            $row->idkelurahan ?? '-',
            $row->nohp ?? '-',
            $row->tgldaftar ? date('d/m/Y', strtotime($row->tgldaftar)) : '-',
        ];
    }

    public function title(): string
    {
        return 'Laporan Demografi';
    }
}
