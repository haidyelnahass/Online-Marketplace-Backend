<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Store extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function items(): HasMany {
        return $this->hasMany(Item::class);
    }

    public function user(): HasOne {
        return $this->hasOne(User::class);
    }
}
