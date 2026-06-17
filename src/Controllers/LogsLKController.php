<?php

namespace Labkey\App\Controllers;

use Illuminate\Support\Facades\Http;

class LogsLKController extends AuthorizeLKController
{
    /**
     * Recupera i log degli accessi. Tutti i parametri sono filtri opzionali.
     * NB: se si valorizza $offset deve essere valorizzato anche $limit.
     *
     * @param  int|null    $labkey_id    Filtra per id del varco (ha priorità su unique_name)
     * @param  string|null $unique_name  Filtra per unique_name del varco (usato solo se labkey_id assente)
     * @param  int|null    $user_id      Filtra per utente
     * @param  string|null $from         Data minima (created_at >=)
     * @param  string|null $to           Data massima (created_at <=)
     * @param  int|null    $last_id_log  Restituisce solo i log con id > last_id_log
     * @param  string|null $tag          Filtra per tag dell'utente
     * @param  int|null    $limit
     * @param  int|null    $offset       Richiede $limit
     * @return array  Mappa indicizzata per id_log
     */
    public function getLogs(
        int|null    $labkey_id   = null,
        string|null $unique_name = null,
        int|null    $user_id     = null,
        string|null $from        = null,
        string|null $to          = null,
        int|null    $last_id_log = null,
        string|null $tag         = null,
        int|null    $limit       = null,
        int|null    $offset      = null
    ): array {
        $response = Http::withToken($this->getToken())->get($this->url . 'getlogs', get_defined_vars());
        $this->badRequest($response);
        if ($response->json('status') != 'KO') {
            return $response->json('message');
        }
        return [];
    }
}
