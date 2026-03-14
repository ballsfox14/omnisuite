<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Tool extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'tipo',
        'marca',
        'modelo',
        'code',
        'description',
        'quantity',
        'accesorios',
    ];

    protected $casts = [
        'accesorios' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tool) {
            $tool->code = $tool->generateCode();
        });

        static::updating(function ($tool) {
            // Si cambian tipo, marca o modelo, regeneramos el código
            if ($tool->isDirty(['tipo', 'marca', 'modelo'])) {
                $tool->code = $tool->generateCode();
            }
        });
    }

    public function generateCode()
    {
        // Si no hay tipo, marca o modelo, lanzamos excepción o usamos un valor por defecto
        if (empty($this->tipo) || empty($this->marca) || empty($this->modelo)) {
            throw new \Exception('Para generar el código, debe especificar tipo, marca y modelo.');
        }

        // Buscar el último número para esta combinación
        $lastTool = self::where('tipo', $this->tipo)
            ->where('marca', $this->marca)
            ->where('modelo', $this->modelo)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastTool) {
            // Extraer el número del código (asumimos formato TIPO-MARCA-MODELO-NNN)
            $parts = explode('-', $lastTool->code);
            $lastNumber = (int) end($parts);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return strtoupper($this->tipo) . '-' . strtoupper($this->marca) . '-' . strtoupper($this->modelo) . '-' . $newNumber;
    }

    public function kits(): BelongsToMany
    {
        return $this->belongsToMany(Kit::class, 'kit_tool')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'tipo', 'marca', 'modelo', 'code', 'quantity', 'description'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}