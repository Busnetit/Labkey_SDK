<?php

namespace Labkey\App\Controllers;

use Illuminate\Support\Facades\Http;

class CustomFieldsUserLKController extends AuthorizeLKController
{

    public function create(int $user_id, int $id_field, string $value, int $can_disable_user = null)
    {
        $params = [];
        $params['value'] = $value;
        if(isset($can_disable_user)){
            $params['can_disable_user'] = $can_disable_user;
        }
        $response=Http::withToken($this->getToken())->post($this->url.'customfieldsuser/'.$user_id.'/'.$id_field.'/create', $params);
        $this->badRequest($response);
        if($response->json('status') != 'OK'){
            return $response->json('message');
        }
        return true;
    }

    public function update(int $user_id,int $id_field,string $value, int $can_disable_user = null)
    {
        $params = [];
        $params['value'] = $value;
        if(isset($can_disable_user)){
            $params['can_disable_user'] = $can_disable_user;
        }
        $response=Http::withToken($this->getToken())->post($this->url.'customfieldsuser/'.$user_id.'/'.$id_field.'/update', $params );
        $this->badRequest($response);
        if($response->json('status') != 'OK'){
            return $response->json('message');
        }
        return true;
    }

    public function getAllByIdUser(int $user_id, int $limit = null,int $offset = null)
    {
       $response = Http::withToken($this->getToken())->get($this->url."customfieldsuser/".$user_id, ["limit" => $limit,"offset" => $offset] );
        if ($response->status() !== 404) {
            $this->badRequest($response);
        }
        if ($response->json('status') != 'KO') {
            return $response->json('message');
        }
        return [];
    }

    public function getFieldUser(int $user_id, int $id_field)
    {
        $response = Http::withToken($this->getToken())->get($this->url . 'customfieldsuser/' . $user_id . "/" . $id_field);
        if ($response->status() !== 404) {
            $this->badRequest($response);
        }
        if ($response->json('status') != 'KO') {
            return $response->json('message');
        }
        return [];
    }
}