<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Store extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function items(): BelongsToMany {
        return $this->belongsToMany(Store::class,'items_stores')->withTimestamps();
    }

    public function user(): HasOne {
        return $this->hasOne(User::class);
    }
}
