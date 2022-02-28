<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ZoomClientService
{

    public function __construct()
    {
    }

    public function checkZoomAccountViaAccessKey($token)
    {
        $response = Http::accept('application/json')
            ->withToken($token)
            ->get('https://api.zoom.us/v2/users/me');
        return $response;
    }
}
