<?php

namespace Labkey\App\Controllers;

use Illuminate\Support\Facades\Http;

class TemplateLKController extends AuthorizeLKController {

    /**
     * @param int $user_id
     * @param int $template_id
     * @param int $timestamp
     * @return array|mixed
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function addAccessUser(int $user_id,int $template_id,int $timestamp_start, int|null $timestamp_end =null)
    {
       $response = Http::withToken($this->getToken())->withUserAgent('SDK-Labkey')->post($this->url.'templates/addAccessUser',[
        "user_id" => $user_id,
        "template_id" => $template_id,
        "timestamp_start" => $timestamp_start,
        "timestamp_end" => $timestamp_end
       ]);
        $this->badRequest($response);
        if($response->json('status') != 'KO'){
            return $response->json('message');
        }
        return [];
    }
    public function getByID(int $template_id){
        $response =  Http::withToken($this->getToken())->withUserAgent('SDK-Labkey')->get($this->url."templates/detail",get_defined_vars());
        $this->badRequest($response);
        if($response->json('status') != 'KO'){
            return $response->json('message');
        }
        return [];
    }
}