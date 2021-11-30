<?php

namespace App\Http\Middleware;

use App\Models\ConnectionAToken;
use App\Models\ConnectionBToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

class ChangePersonalAccessTokenTable
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        if ($token) {
            Sanctum::usePersonalAccessTokenModel(ConnectionAToken::class);
            $model = Sanctum::$personalAccessTokenModel;
            $accessToken = $model::findToken($token);
            if (!$accessToken) {
                Sanctum::usePersonalAccessTokenModel(ConnectionBToken::class);
                $model = Sanctum::$personalAccessTokenModel;
                $accessToken = $model::findToken($token);
            }
        }
        return $next($request);
    }
}
