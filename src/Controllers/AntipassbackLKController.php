<?php

namespace Labkey\App\Controllers;

use Illuminate\Support\Facades\Http;

class AntipassbackLKController extends AuthorizeLKController
{
    /**
     * Restituisce le impostazioni antipassback di un utente per un varco.
     *
     * @param  int    $user_id
     * @param  string $unique_name  unique_name del varco
     * @return array
     */
    public function get(int $user_id, string $unique_name): array
    {
        $response = Http::withToken($this->getToken())->get($this->url . 'antipassback', get_defined_vars());
        $this->badRequest($response);
        return $response->json();
    }

    /**
     * Crea o aggiorna le impostazioni antipassback di un utente.
     *
     * Struttura di $data:
     * {
     *   "<unique_name>": {
     *     "<id_rele>": {
     *       "is_active": 0|1,
     *       "has_total": 0|1, "has_day": 0|1, "has_week": 0|1, "has_month": 0|1,
     *       "number_total": int, "number_day": int, "number_week": int, "number_month": int
     *     }
     *   }
     * }
     * Al massimo uno tra has_total/has_day/has_week/has_month può essere attivo.
     *
     * @param  int          $user_id
     * @param  array|string $data  Array (verrà codificato in JSON) oppure stringa JSON già pronta
     * @return bool
     */
    public function updateOrCreate(int $user_id, array|string $data): bool
    {
        $params = [
            'user_id' => $user_id,
            'data'    => is_array($data) ? json_encode($data) : $data,
        ];
        $response = Http::withToken($this->getToken())->post($this->url . 'antipassback/update_or_create', $params);
        $this->badRequest($response);
        return $response->json('status') === 'OK';
    }
}
