<?php

namespace Labkey\App\Controllers;

use Illuminate\Support\Facades\Http;

class CreditsLKController extends AuthorizeLKController
{
    /**
     *
     * @param array|int|null $user_id
     * @param array|int|null $id_log
     * @param array|string|null $unique_name
     * @return array
     * @throws \Illuminate\Http\Client\ConnectionException
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function getCredits(array|int|null $user_id=null, array|int|null $id_log=null, array|string|null $unique_name=null  ):array{
        $response=Http::withToken($this->getToken())->get($this->url.'getcredits',get_defined_vars());
        $this->badRequest($response);
        return $response->json('message');
    }

    public function getPrices(array|int|null $user_type_id=null, array|string|null $unique_name=null  ):array{
        $response=Http::withToken($this->getToken())->get($this->url.'getprices',get_defined_vars());
        $this->badRequest($response);
        if($response->json('status') != 'KO'){
            return $response->json('message');
        }
        return[];
    }

    public function recharge(int $user_id, float $amount){
        $response=Http::withToken($this->getToken())->post($this->url.'recharge',get_defined_vars());
        $this->badRequest($response);
        if($response->json('status') != 'KO'){
            return $response->json('message');
        }
        return[];
    }

}