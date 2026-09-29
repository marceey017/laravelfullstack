<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Manufacturer extends Model
{
    protected $fillable = [
        'name',
        'country',
    ];

    public function carModels(): HasMany
    {
        return $this->hasMany(CarModel::class);
    }
}
