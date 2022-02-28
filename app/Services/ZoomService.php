<?php
namespace App\Services;

use App\Enums\StatusCode;

class ZoomService
{

    public function __construct()
    {
    }

    public function zoomClient($url, $method, $token)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . $token,
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $curlinfo = curl_getinfo($curl);

        curl_close($curl);

        if ($curlinfo['http_code'] != StatusCode::OK) {
            $data = json_decode($err);
        } else {
            $data = json_decode($response);
        }
        return [
            'http_code' => $curlinfo['http_code'],
            'data' => json_decode($response)
        ];
    }
}
