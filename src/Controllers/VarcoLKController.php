<?php

namespace Labkey\App\Controllers;

use Illuminate\Support\Facades\Http;

class VarcoLKController extends AuthorizeLKController
{
    public function getVarcos(string $unique_name = '', int|null $labkey_id = null ,string $key_tipe = ''):array {
        $response=Http::withToken($this->getToken())->get($this->url.'getlabkeys',get_defined_vars());
        $this->badRequest($response);
        if($response->json('status') != 'KO'){
            return $response->json('message');
        }
        return [];
    }
    public function dropAccess($involved_associations){
        $response = Http::withToken($this->getToken())->delete($this->url."dropaccess",get_defined_vars());
        $this->badRequest($response);
        if($response->json('status') != 'KO'){
            return $response->json('message');
        }
        return [];
    }
    public function isAccessible(string $unique_name,int $from_date,int $to_date,string $id_rele)
    {
        $response=Http::withToken($this->getToken())->post($this->url.'isAccessible',get_defined_vars());
        $this->badRequest($response);
        return $response->json('message');
       
    }

    public function editaccess(int $user_id,int $key_id,string $data){
        $response=Http::withToken($this->getToken())->post($this->url.'grantaccess',get_defined_vars());
        $this->badRequest($response);
        if($response->json('status') != 'KO'){
            return $response->json('message');
        }
        return [];
    }

}