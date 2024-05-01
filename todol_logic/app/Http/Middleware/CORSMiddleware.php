<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CORSMiddleware
{
    public function handle($request, Closure $next)
    {
        $headers = [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'POST,GET,PATCH,PUT,DELETE,OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type,API-KEY'
        ];

        if ($request->isMethod('OPTIONS')) {
            return response()->json('', 200, $headers);
        }

        $response = $next($request);

        foreach ($headers as $key => $value) {
            $response->header($key, $value);
        }

        return $response;
    }

}
