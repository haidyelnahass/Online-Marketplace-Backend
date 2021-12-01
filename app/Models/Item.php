<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function owner(): BelongsTo {
        return $this->belongsTo(User::class, 'owner_id');
    }
    public function creator(): BelongsTo {
        return $this->belongsTo(User::class, 'creator_id');
    }
    public function stores(): BelongsToMany {
        return $this->belongsToMany(Store::class,'items_stores');
    }

    public function payment(): HasMany {
        return $this->hasMany(Payment::class);
    }
}
