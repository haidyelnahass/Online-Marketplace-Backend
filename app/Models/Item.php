<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
