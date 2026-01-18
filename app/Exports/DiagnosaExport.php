<?php

namespace App\Exports;

use App\Models\Rawat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Facades\DB;

class DiagnosaExport implements FromCollection, WithHeadings, WithMapping, WithTitle
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
        $query = Rawat::select('icdx', DB::raw('COUNT(*) as total'))
            ->whereNotNull('icdx')
            ->where('icdx', '!=', '');

        if ($this->tanggal_mulai && $this->tanggal_selesai) {
            $query->whereBetween('tglmasuk', [$this->tanggal_mulai . ' 00:00:00', $this->tanggal_selesai . ' 23:59:59']);
        }

        return $query->groupBy('icdx')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode ICD-X',
            'Jumlah Pasien',
        ];
    }

    public function map($row): array
    {
        return [
            $row->icdx ?? '-',
            $row->total ?? 0,
        ];
    }

    public function title(): string
    {
        return '10 Diagnosa Terbanyak';
    }
}
