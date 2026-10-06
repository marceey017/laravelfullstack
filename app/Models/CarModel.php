<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarModel extends Model
{
    protected $fillable = [
        'manufacturer_id',
        'name',
        'release_year',
    ];

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function scopeFilterListing(
        Builder $query,
        string $search,
        ?int $manufacturerId
    ): Builder {
        if ($search !== '') {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($manufacturerId !== null) {
            $query->where('manufacturer_id', $manufacturerId);
        }

        return $query;
    }
}
