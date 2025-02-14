<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\PersonalAccessToken;

class ConnectionAToken extends PersonalAccessToken
{
    protected $connection = 'mysql';
    protected $table = 'personal_access_tokens';
}
