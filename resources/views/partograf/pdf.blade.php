<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Partograf</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 14px;
        }

        .patient-info {
            width: 100%;
            margin-bottom: 20px;
        }

        .patient-info td {
            padding: 5px;
            vertical-align: top;
        }

        .label {
            font-weight: bold;
            width: 120px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 10px;
            background-color: #eee;
            padding: 5px;
        }

        /* Partograf Chart Styles */
        .chart-container {
            position: relative;
            width: 100%;
            height: 300px;
            border: 2px solid #333;
            margin-bottom: 20px;
            background: white;
        }

        .chart-grid {
            position: relative;
            width: 100%;
            height: 100%;
            background-image:
                repeating-linear-gradient(0deg, #ddd 0px, #ddd 1px, transparent 1px, transparent 30px),
                repeating-linear-gradient(90deg, #ddd 0px, #ddd 1px, transparent 1px, transparent 40px);
        }

        .chart-point {
            position: absolute;
            width: 8px;
            height: 8px;
            background: #009EF7;
            border-radius: 50%;
            border: 2px solid white;
            transform: translate(-50%, -50%);
        }

        .chart-line {
            position: absolute;
            height: 2px;
            background: #009EF7;
            transform-origin: left center;
        }

        .chart-alert-line {
            background: #FFC700;
        }

        .chart-action-line {
            background: #F1416C;
        }

        .chart-axis-label {
            position: absolute;
            font-size: 10px;
            color: #666;
        }

        .chart-title {
            text-align: center;
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>RSAU dr. Norman T Lubis</h1>
        <h2>Laporan Observasi Partograf</h2>
    </div>

    <table class="patient-info">
        <tr>
            <td class="label">Nama Pasien:</td>
            <td>{{ $laborRecord->visit->pasien->nama_pasien ?? $laborRecord->visit->pasien->nama ?? '-' }}</td>
            <td class="label">No. RM:</td>
            <td>{{ $laborRecord->patient_no_rm }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Masuk:</td>
            <td>{{ $laborRecord->admission_date->format('d/m/Y H:i') }}</td>
            <td class="label">Usia Kehamilan:</td>
            <td>{{ $laborRecord->gestational_age }} minggu</td>
        </tr>
        <tr>
            <td class="label">Dokter:</td>
            <td>{{ $laborRecord->doctor->nama_dokter ?? '-' }}</td>
            <td class="label">Bidan:</td>
            <td>{{ $laborRecord->midwife->detail->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Status:</td>
            <td>
                @if($laborRecord->status == 'ongoing') Berlangsung
                @elseif($laborRecord->status == 'completed') Selesai
                @elseif($laborRecord->status == 'referred') Rujukan
                @endif
            </td>
            <td class="label">Ketuban Pecah:</td>
            <td>
                @if($laborRecord->has_ruptured_membranes)
                Ya ({{ \Carbon\Carbon::parse($laborRecord->rupture_date)->format('d/m/Y H:i') }})
                @else
                Tidak
                @endif
            </td>
        </tr>
    </table>

    @if($laborRecord->initial_risk_assessment && count($laborRecord->getActiveRiskFactors()) > 0)
    <div class="section-title">Penilaian Awal Risiko - {{ $laborRecord->risk_label }}</div>
    <table class="patient-info">
        <tr>
            <td class="label">Faktor Risiko yang Teridentifikasi:</td>
            <td>
                @php
                $riskFactors = config('partograf.risk_factors');
                $activeFactors = $laborRecord->getActiveRiskFactors();
                @endphp
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($activeFactors as $key)
                    <li>{{ $riskFactors[$key] ?? $key }}</li>
                    @endforeach
                </ul>
            </td>
        </tr>
        @if($laborRecord->initial_assessment_notes)
        <tr>
            <td class="label">Catatan:</td>
            <td>{{ $laborRecord->initial_assessment_notes }}</td>
        </tr>
        @endif
    </table>
    @endif

    {{-- Partograf Charts --}}
    @if($laborRecord->progresses->count() > 0 || $laborRecord->fetalMonitorings->count() > 0)
    <div class="section-title">Grafik Partograf</div>

    {{-- Cervical Dilation Chart --}}
    @if($laborRecord->progresses->count() > 0)
    <div class="chart-title">Pembukaan Serviks (cm)</div>
    <div class="chart-container" style="height: 250px;">
        <div class="chart-grid">
            @php
            $maxHours = 12;
            $chartWidth = 100; // percentage
            $chartHeight = 250;
            $maxDilation = 10;

            // Plot cervical dilation points
            foreach($laborRecord->progresses as $index => $progress) {
            $hoursSinceStart = $progress->observation_time->diffInHours($laborRecord->labor_start_time);
            if ($hoursSinceStart > $maxHours) continue;

            $x = ($hoursSinceStart / $maxHours) * $chartWidth;
            $y = 100 - (($progress->cervical_dilatation / $maxDilation) * 100);
            }
            @endphp

            @foreach($laborRecord->progresses as $progress)
            @php
            $hoursSinceStart = $progress->observation_time->diffInHours($laborRecord->labor_start_time);
            if ($hoursSinceStart > $maxHours) continue;

            $x = ($hoursSinceStart / $maxHours) * 95;
            $y = 95 - (($progress->cervical_dilatation / $maxDilation) * 90);
            @endphp
            <div class="chart-point" style="left: {{ $x }}%; top: {{ $y }}%;"></div>
            @endforeach

            {{-- Y-axis labels --}}
            @for($i = 0; $i <= 10; $i +=2)
                @php $yPos=95 - (($i / 10) * 90); @endphp
                <div class="chart-axis-label" style="left: -25px; top: {{ $yPos }}%;">{{ $i }}
        </div>
        @endfor

        {{-- X-axis labels --}}
        @for($h = 0; $h <= 12; $h +=2)
            @php
            $xPos=($h / 12) * 95;
            $timeLabel=$laborRecord->labor_start_time->copy()->addHours($h)->format('H:i');
            @endphp
            <div class="chart-axis-label" style="left: {{ $xPos }}%; bottom: -20px;">{{ $timeLabel }}
            </div>
            @endfor
    </div>
    </div>
    @endif

    {{-- FHR Chart --}}
    @if($laborRecord->fetalMonitorings->count() > 0)
    <div class="chart-title">Detak Jantung Janin (bpm)</div>
    <div class="chart-container" style="height: 200px;">
        <div class="chart-grid">
            @php
            $maxHours = 12;
            $minFHR = 100;
            $maxFHR = 180;
            @endphp

            @foreach($laborRecord->fetalMonitorings as $monitoring)
            @php
            $hoursSinceStart = $monitoring->observation_time->diffInHours($laborRecord->labor_start_time);
            if ($hoursSinceStart > $maxHours) continue;

            $x = ($hoursSinceStart / $maxHours) * 95;
            $y = 95 - ((($monitoring->fetal_heart_rate - $minFHR) / ($maxFHR - $minFHR)) * 90);

            // Color code: red if abnormal
            $color = ($monitoring->fetal_heart_rate >= 120 && $monitoring->fetal_heart_rate <= 160) ? '#009EF7' : '#F1416C' ;
                @endphp
                <div class="chart-point" style="left: {{ $x }}%; top: {{ $y }}%; background: {{ $color }};">
        </div>
        @endforeach

        {{-- Y-axis labels --}}
        @for($i = 100; $i <= 180; $i +=20)
            @php $yPos=95 - ((($i - 100) / 80) * 90); @endphp
            <div class="chart-axis-label" style="left: -30px; top: {{ $yPos }}%;">{{ $i }}
    </div>
    @endfor

    {{-- X-axis labels --}}
    @for($h = 0; $h <= 12; $h +=2)
        @php
        $xPos=($h / 12) * 95;
        $timeLabel=$laborRecord->labor_start_time->copy()->addHours($h)->format('H:i');
        @endphp
        <div class="chart-axis-label" style="left: {{ $xPos }}%; bottom: -20px;">{{ $timeLabel }}</div>
        @endfor
        </div>
        </div>
        @endif

        {{-- Maternal Vitals Chart --}}
        @if($laborRecord->maternalVitals->count() > 0)
        <div class="chart-title">Tanda Vital Ibu</div>
        <div class="chart-container" style="height: 200px;">
            <div class="chart-grid">
                @php
                $maxHours = 12;
                @endphp

                {{-- Plot Pulse (60-120 bpm) --}}
                @foreach($laborRecord->maternalVitals as $vital)
                @php
                $hoursSinceStart = $vital->observation_time->diffInHours($laborRecord->labor_start_time);
                if ($hoursSinceStart > $maxHours || !$vital->pulse_rate) continue;

                $x = ($hoursSinceStart / $maxHours) * 95;
                $y = 95 - ((($vital->pulse_rate - 60) / 60) * 90);
                @endphp
                <div class="chart-point" style="left: {{ $x }}%; top: {{ $y }}%; background: #F1416C; border-color: #F1416C;"></div>
                @endforeach

                {{-- Plot BP Systolic (90-180 mmHg) as triangles --}}
                @foreach($laborRecord->maternalVitals as $vital)
                @php
                $hoursSinceStart = $vital->observation_time->diffInHours($laborRecord->labor_start_time);
                if ($hoursSinceStart > $maxHours || !$vital->blood_pressure_systolic) continue;

                $x = ($hoursSinceStart / $maxHours) * 95;
                $y = 95 - ((($vital->blood_pressure_systolic - 90) / 90) * 90);
                @endphp
                <div style="position: absolute; left: {{ $x }}%; top: {{ $y }}%; width: 0; height: 0; border-left: 5px solid transparent; border-right: 5px solid transparent; border-bottom: 8px solid #FFC700; transform: translate(-50%, -50%);"></div>
                @endforeach

                {{-- Plot Temperature (35-40°C) as squares --}}
                @foreach($laborRecord->maternalVitals as $vital)
                @php
                $hoursSinceStart = $vital->observation_time->diffInHours($laborRecord->labor_start_time);
                if ($hoursSinceStart > $maxHours || !$vital->temperature) continue;

                $x = ($hoursSinceStart / $maxHours) * 95;
                $y = 95 - ((($vital->temperature - 35) / 5) * 90);
                @endphp
                <div style="position: absolute; left: {{ $x }}%; top: {{ $y }}%; width: 6px; height: 6px; background: #50CD89; transform: translate(-50%, -50%);"></div>
                @endforeach

                {{-- Y-axis labels (simplified - showing pulse range) --}}
                @for($i = 60; $i <= 120; $i +=20)
                    @php $yPos=95 - ((($i - 60) / 60) * 90); @endphp
                    <div class="chart-axis-label" style="left: -30px; top: {{ $yPos }}%;">{{ $i }}
            </div>
            @endfor

            {{-- X-axis labels --}}
            @for($h = 0; $h <= 12; $h +=2)
                @php
                $xPos=($h / 12) * 95;
                $timeLabel=$laborRecord->labor_start_time->copy()->addHours($h)->format('H:i');
                @endphp
                <div class="chart-axis-label" style="left: {{ $xPos }}%; bottom: -20px;">{{ $timeLabel }}
                </div>
                @endfor
        </div>
        </div>
        <div style="margin-top: 10px; margin-bottom: 15px; font-size: 10px; text-align: center;">
            <span style="color: #F1416C;">Nadi</span> &nbsp;
            <span style="color: #FFC700;">TD Systolic</span> &nbsp;
            <span style="color: #50CD89;">Suhu</span>
        </div>
        @endif
        @endif

        <div class="section-title">Kemajuan Persalinan</div>
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Pembukaan (cm)</th>
                    <th>Penurunan</th>
                    <th>Molding</th>
                    <th>Posisi</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laborRecord->progresses()->orderBy('observation_time', 'asc')->get() as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->observation_time)->format('d/m/Y H:i') }}</td>
                    <td>{{ $item->cervical_dilatation }}</td>
                    <td>{{ $item->fetal_head_descent }}</td>
                    <td>{{ $item->molding }}</td>
                    <td>{{ $item->position }}</td>
                    <td>{{ $item->notes }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="section-title">Kontraksi Uterus</div>
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Frekuensi (/10 menit)</th>
                    <th>Durasi (detik)</th>
                    <th>Intensitas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laborRecord->contractions()->orderBy('observation_time', 'asc')->get() as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->observation_time)->format('d/m/Y H:i') }}</td>
                    <td>{{ $item->contractions_per_10min }}</td>
                    <td>{{ $item->duration_seconds }}</td>
                    <td>{{ $item->intensity }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center;">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="section-title">Detak Jantung Janin (DJJ)</div>
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>DJJ (bpm)</th>
                    <th>Warna Air Ketuban</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laborRecord->fetalMonitorings()->orderBy('observation_time', 'asc')->get() as $fetal)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($fetal->observation_time)->format('d/m/Y H:i') }}</td>
                    <td style="{{ ($fetal->fetal_heart_rate >= 120 && $fetal->fetal_heart_rate <= 160) ? '' : 'background-color: #ffe6e6;' }}">
                        {{ $fetal->fetal_heart_rate }}
                    </td>
                    <td>{{ $fetal->amniotic_fluid_color ?? '-' }}</td>
                    <td>{{ $fetal->notes ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center;">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="section-title">Tanda Vital Ibu</div>
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Nadi (bpm)</th>
                    <th>TD (mmHg)</th>
                    <th>Suhu (°C)</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laborRecord->maternalVitals()->orderBy('observation_time', 'asc')->get() as $vital)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($vital->observation_time)->format('d/m/Y H:i') }}</td>
                    <td>{{ $vital->pulse_rate ?? '-' }}</td>
                    <td>{{ $vital->blood_pressure_systolic ?? '-' }}/{{ $vital->blood_pressure_diastolic ?? '-' }}</td>
                    <td>{{ $vital->temperature ?? '-' }}</td>
                    <td>{{ $vital->notes ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="section-title">Denyut Jantung Janin</div>
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>DJJ (bpm)</th>
                    <th>Air Ketuban</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laborRecord->fetalMonitorings()->orderBy('observation_time', 'asc')->get() as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->observation_time)->format('d/m/Y H:i') }}</td>
                    <td>{{ $item->fetal_heart_rate }}</td>
                    <td>{{ $item->amniotic_fluid_color }}</td>
                    <td>{{ $item->notes }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center;">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="section-title">Tanda Vital Ibu</div>
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>TD (mmHg)</th>
                    <th>Nadi</th>
                    <th>Suhu</th>
                    <th>RR</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laborRecord->maternalVitals()->orderBy('observation_time', 'asc')->get() as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->observation_time)->format('d/m/Y H:i') }}</td>
                    <td>{{ $item->blood_pressure_systolic }}/{{ $item->blood_pressure_diastolic }}</td>
                    <td>{{ $item->pulse_rate }}</td>
                    <td>{{ $item->temperature }}</td>
                    <td>{{ $item->respiration_rate }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="section-title">Urine</div>
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Volume (ml)</th>
                    <th>Protein</th>
                    <th>Aseton</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laborRecord->maternalUrines()->orderBy('observation_time', 'asc')->get() as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->observation_time)->format('d/m/Y H:i') }}</td>
                    <td>{{ $item->volume_ml }}</td>
                    <td>{{ $item->protein }}</td>
                    <td>{{ $item->acetone }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center;">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="section-title">Obat & Cairan</div>
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Nama Obat</th>
                    <th>Dosis</th>
                    <th>Rute</th>
                    <th>Jenis</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laborRecord->medications()->orderBy('administration_time', 'asc')->get() as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->administration_time)->format('d/m/Y H:i') }}</td>
                    <td>{{ $item->medication_name }}</td>
                    <td>{{ $item->dosage }}</td>
                    <td>{{ $item->route }}</td>
                    <td>{{ $item->medication_type }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>

</body>

</html>