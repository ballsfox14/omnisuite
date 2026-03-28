<?php

namespace Modules\Contracts\Entities;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $fillable = ['name', 'description'];

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_zone')
            ->withPivot('price')
            ->withTimestamps();
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }
}