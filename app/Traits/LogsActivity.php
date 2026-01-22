<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Str;

trait LogsActivity
{
    /**
     * Boot the trait
     */
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logActivity('created');
        });

        static::updated(function ($model) {
            $model->logActivity('updated');
        });

        static::deleted(function ($model) {
            $model->logActivity('deleted');
        });
    }

    /**
     * Log activity
     */
    public function logActivity(string $event, array $customProperties = [])
    {
        if (!auth()->check()) {
            return;
        }

        $properties = $this->prepareProperties($event);
        $properties = array_merge($properties, $customProperties);

        $log = [
            'log_name' => $this->getLogName(),
            'description' => $this->getActivityDescription($event),
            'subject_type' => get_class($this),
            'subject_id' => $this->getKey(),
            'causer_type' => 'App\Models\User',
            'causer_id' => auth()->id(),
            'event' => $event,
            'properties' => $properties,
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name ?? auth()->user()->username,
            'user_role' => $this->getUserRole(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
        ];

        // Add medical record specific fields
        $log = array_merge($log, $this->getMedicalRecordFields());

        ActivityLog::create($log);
    }

    /**
     * Log custom activity
     */
    public function logCustomActivity(string $description, array $properties = [])
    {
        if (!auth()->check()) {
            return;
        }

        $log = [
            'log_name' => $this->getLogName(),
            'description' => $description,
            'subject_type' => get_class($this),
            'subject_id' => $this->getKey(),
            'causer_type' => 'App\Models\User',
            'causer_id' => auth()->id(),
            'event' => 'custom',
            'properties' => $properties,
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name ?? auth()->user()->username,
            'user_role' => $this->getUserRole(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
        ];

        $log = array_merge($log, $this->getMedicalRecordFields());

        ActivityLog::create($log);
    }

    /**
     * Prepare properties based on event
     */
    protected function prepareProperties(string $event): array
    {
        $properties = [];

        if ($event === 'created') {
            $properties['attributes'] = $this->attributesToArray();
        } elseif ($event === 'updated') {
            $properties['attributes'] = $this->getDirty();
            $properties['old'] = array_intersect_key($this->getOriginal(), $this->getDirty());
        } elseif ($event === 'deleted') {
            $properties['attributes'] = $this->getOriginal();
        }

        return $properties;
    }

    /**
     * Get log name (category)
     */
    protected function getLogName(): string
    {
        $modelName = class_basename(get_class($this));

        $categories = [
            'Rawat' => 'kunjungan',
            'RekapMedis' => 'rekam_medis',
            'DetailRekapMedis' => 'rekam_medis',
            'TindakLanjut' => 'tindak_lanjut',
            'Pasien' => 'pasien',
            'Dokter' => 'dokter',
            'Perawat' => 'perawat',
        ];

        return $categories[$modelName] ?? Str::snake($modelName);
    }

    /**
     * Get activity description
     */
    protected function getActivityDescription(string $event): string
    {
        $modelName = $this->getModelDisplayName();
        $identifier = $this->getIdentifier();

        $descriptions = [
            'created' => "Membuat {$modelName} baru: {$identifier}",
            'updated' => "Memperbarui {$modelName}: {$identifier}",
            'deleted' => "Menghapus {$modelName}: {$identifier}",
            'viewed' => "Melihat {$modelName}: {$identifier}",
        ];

        return $descriptions[$event] ?? "Aktivitas pada {$modelName}: {$identifier}";
    }

    /**
     * Get model display name
     */
    protected function getModelDisplayName(): string
    {
        $modelName = class_basename(get_class($this));

        $displayNames = [
            'Rawat' => 'Kunjungan Pasien',
            'RekapMedis' => 'Rekam Medis',
            'DetailRekapMedis' => 'Detail Rekam Medis',
            'TindakLanjut' => 'Tindak Lanjut',
            'Pasien' => 'Data Pasien',
            'Dokter' => 'Data Dokter',
        ];

        return $displayNames[$modelName] ?? $modelName;
    }

    /**
     * Get identifier for logging
     */
    protected function getIdentifier(): string
    {
        if (isset($this->no_rm)) {
            return "RM: {$this->no_rm}";
        }

        if (isset($this->nama_pasien)) {
            return $this->nama_pasien;
        }

        if (isset($this->nama_dokter)) {
            return $this->nama_dokter;
        }

        return "ID: {$this->getKey()}";
    }

    /**
     * Get user role
     */
    protected function getUserRole(): ?string
    {
        if (!auth()->check()) {
            return null;
        }

        $user = auth()->user();

        // Cek dari tabel privilege
        if (isset($user->idpriv)) {
            $roles = [
                7 => 'dokter',
                14 => 'perawat',
                18 => 'perawat',
                29 => 'perawat',
                20 => 'coder',
            ];
            return $roles[$user->idpriv] ?? 'staff';
        }

        return 'user';
    }

    /**
     * Get medical record fields
     */
    protected function getMedicalRecordFields(): array
    {
        $fields = [
            'no_rm' => null,
            'idrawat' => null,
            'poli' => null,
            'dokter' => null,
        ];

        // Get from direct properties
        if (isset($this->no_rm)) {
            $fields['no_rm'] = $this->no_rm;
        }

        if (isset($this->idrawat)) {
            $fields['idrawat'] = $this->idrawat;
        }

        // Get from relationships
        if ($this->relationLoaded('rawat') && $this->rawat) {
            $fields['no_rm'] = $this->rawat->no_rm ?? null;
            $fields['idrawat'] = $this->rawat->id ?? null;

            if ($this->rawat->relationLoaded('poli') && $this->rawat->poli) {
                $fields['poli'] = $this->rawat->poli->poli ?? null;
            }

            if ($this->rawat->relationLoaded('dokter') && $this->rawat->dokter) {
                $fields['dokter'] = $this->rawat->dokter->nama_dokter ?? null;
            }
        }

        if ($this->relationLoaded('poli') && $this->poli) {
            $fields['poli'] = $this->poli->poli ?? $this->poli->nama ?? null;
        }

        if ($this->relationLoaded('dokter') && $this->dokter) {
            $fields['dokter'] = $this->dokter->nama_dokter ?? null;
        }

        return $fields;
    }

    /**
     * Get activities for this model
     */
    public function activities()
    {
        return ActivityLog::where('subject_type', get_class($this))
                          ->where('subject_id', $this->getKey())
                          ->orderBy('created_at', 'desc')
                          ->get();
    }
}
