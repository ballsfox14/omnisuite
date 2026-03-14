<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Loan extends Model
{
    use LogsActivity;
    protected $fillable = [
        'user_id',
        'notes',
        'loaned_at',
        'returned_at',
        'team_size',
    ];

    protected $casts = [
        'loaned_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['user_id', 'notes', 'loaned_at', 'returned_at', 'team_size'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Relación con el usuario principal
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relación con los items prestados
    public function items(): HasMany
    {
        return $this->hasMany(LoanItem::class);
    }

    // Relación muchos a muchos con usuarios adicionales
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'loan_user')->withTimestamps();
    }

    // Verifica si el préstamo está activo
    public function isActive(): bool
    {
        return is_null($this->returned_at);
    }
}