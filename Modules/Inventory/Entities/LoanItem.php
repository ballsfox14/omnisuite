<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class LoanItem extends Model
{
    use LogsActivity;
    protected $fillable = [
        'loan_id',
        'loanable_type',
        'loanable_id',
        'quantity',
    ];

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function loanable(): MorphTo
    {
        return $this->morphTo();
    }

      public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['loan_id', 'loanable_type', 'loanable_id', 'quantity'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}