<?php

namespace Modules\Contracts\Entities;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = ['name', 'description', 'base_price'];

    public function zones()
    {
        return $this->belongsToMany(Zone::class, 'package_zone')
            ->withPivot('price')
            ->withTimestamps();
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }
}