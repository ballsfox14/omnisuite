<?php

namespace Modules\Contracts\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Str;

class Contract extends Model
{
    protected $fillable = [
        'client_name',
        'status',
        'created_by',
        'package_id',
        'zone_id',
        'price',
        'signature',
        'signature_date',
        'signature_token',
        'signed_at',
        'signature_method',
    ];

    protected $casts = [
        'status' => 'string',
        'signature_date' => 'datetime',
        'signed_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pendiente' => 'Pendiente',
            'en_revision' => 'En revisión',
            'aprobado' => 'Aprobado',
            'rechazado' => 'Rechazado',
        ];
        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pendiente' => 'yellow',
            'en_revision' => 'blue',
            'aprobado' => 'green',
            'rechazado' => 'red',
        ];
        return $colors[$this->status] ?? 'gray';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($contract) {
            if (empty($contract->signature_token)) {
                $contract->signature_token = Str::random(32);
            }
        });
    }
}