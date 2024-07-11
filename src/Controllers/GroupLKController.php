<?php

namespace Labkey\App\Controllers;

use Illuminate\Support\Facades\Http;

class GroupLKController extends AuthorizeLKController
{
    /**
     * @throws RequestException
     */
    public function get(array|null|string $id=null){
        $response=Http::withToken($this->getToken())->get($this->url.'getGroup',$id);
        $this->badRequest($response);
        if($response->json('status') != 'KO'){
            return $response->json('message');
        }
        return[];
    }

    /**
     * @throws RequestException
     */
    public function grantAccessGroup(string $user_id, string $group_id){
        $response=Http::withToken($this->getToken())->post($this->url.'grantAccessGroup',get_defined_vars());
        $this->badRequest($response);
        if($response->json('status') != 'KO'){
            return $response->json('message');
        }
        return[];
    }

}