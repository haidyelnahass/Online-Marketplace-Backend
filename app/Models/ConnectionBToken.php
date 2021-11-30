<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\PersonalAccessToken;

class ConnectionBToken extends PersonalAccessToken
{
    protected $connection = 'mysql2';
    protected $table = 'personal_access_tokens';
}
