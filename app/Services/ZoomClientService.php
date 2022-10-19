<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Firebase\JWT\JWT;

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

    public function getUserList($token)
    {
        $users = [];
        $response = Http::accept('application/json')
            ->withToken($token)
            ->get('https://api.zoom.us/v2/users');
        $maxPage = $response->json('page_count');
        for ($page = 0; $page < $maxPage; $page++) {
            $response = Http::accept('application/json')
                ->withToken($token)
                ->get('https://api.zoom.us/v2/users?page=' . $page);
            $userPagings = $response->json('users');
            $users = array_merge($users, $userPagings);
        }
        return $users;
    }

    public function getZoomAccessToken($api_key, $api_secret)
    {
        $payload = array(
            "iss" => $api_key,
            'exp' => time() + 3600,
        );
        return JWT::encode($payload, $api_secret, 'HS256');
    }

    public function createZoomMeeting($token, $zoom_user_id, $object)
    {
        $response = Http::withToken($token)
        ->post('https://api.zoom.us/v2/users/' . $zoom_user_id . '/meetings', $object);
        return $response;
    }
}
