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

}