<?php

namespace Labkey\App\Controllers;

use Illuminate\Support\Facades\Http;

class KeysLKController extends AuthorizeLKController
{
    /**
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function get(bool $unused = false)
    {
        $response = Http::withToken($this->getToken())->get($this->url . ($unused ? 'getunusednfc' : 'getallnfc'));
        $this->badRequest($response);
        if($response->json('status') != 'KO'){
            return $response->json('message');
        }
        return [];
    }

    public function updatePinpad(int $user_id, int $new_pinpad_key_code)
    {
        $response = Http::withToken($this->getToken())->post($this->url . 'updatepinpad', get_defined_vars());
        $this->badRequest($response);
        if($response->json('status') != 'KO'){
            return $response->json('message');
        }
        return [];
    }

    public function getUnusedNFC()
    {
        $response = Http::withToken($this->getToken())->get($this->url . "getunusednfc");
        dd($response->body());
        $this->badRequest($response);
        if($response->json('status') != 'KO'){
            return $response->json('message');
        }
        return [];
    }

    /**
     * Creates and NFC key
     */
    public function create(string $nfc_key_code, string $nfc_key_name, bool|null $force_hex = false): array
    {
        $response = Http::withToken($this->getToken())->put($this->url . "addkey", get_defined_vars());
        dd($response->body());
        $this->badRequest($response);
        return $response->json('message');
    }

}