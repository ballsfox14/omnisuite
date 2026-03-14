<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Kit extends Model
{
    use LogsActivity;
    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($kit) {
            $kit->code = $kit->generateCode();
        });
    }

    public function generateCode()
    {
        // Prefijo fijo para kits
        $prefix = 'KIT';

        // Obtener el último kit creado para determinar el siguiente número
        $lastKit = self::orderBy('id', 'desc')->first();

        if ($lastKit) {
            // Extraer el número del código (asumimos formato KIT-XXXXX)
            $lastCode = $lastKit->code;
            $parts = explode('-', $lastCode);
            $lastNumber = (int) end($parts);
            $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '00001';
        }

        return $prefix . '-' . $newNumber;
    }

    public function tools(): BelongsToMany
    {
        return $this->belongsToMany(Tool::class, 'kit_tool')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function loans(): MorphMany
    {
        return $this->morphMany(Loan::class, 'loanable');
    }

    public function getMobileUrlAttribute()
    {
        return route('mobile.kit.show', $this->code);
    }

    public function getQrCodeAttribute()
    {
        return QrCode::size(200)
            ->backgroundColor(255, 255, 255)
            ->color(0, 51, 102) // #003366
            ->generate($this->mobile_url);
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'code', 'description'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}