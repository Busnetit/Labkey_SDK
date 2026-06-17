<?php

namespace Labkey\App\Controllers;

use Illuminate\Support\Facades\Http;

class FestivitaLKController extends AuthorizeLKController
{
    /**
     * Restituisce la lista paginata delle festività, oppure il dettaglio di una singola (con varcos e utenti).
     */
    public function getAll(int $id = null, int $limit = 50, int $offset = 0): array
    {
        $params = array_filter(compact('id', 'limit', 'offset'), fn($v) => $v !== null);
        $response = Http::withToken($this->getToken())->get($this->url . 'festivita', $params);
        $this->badRequest($response);
        if ($response->json('status') !== 'KO') {
            return $response->json('message');
        }
        return [];
    }

    /**
     * Restituisce il conteggio totale delle festività.
     */
    public function getCount(): array
    {
        $response = Http::withToken($this->getToken())->get($this->url . 'festivita/count');
        $this->badRequest($response);
        if ($response->json('status') !== 'KO') {
            return $response->json('message');
        }
        return [];
    }

    /**
     * Verifica se un dato momento è una festività.
     *
     * @param  string      $datetime     Formato Y-m-d H:i:s
     * @param  string|null $unique_name  Filtro per centralina (unique_name)
     * @param  int|null    $labkey_id    Filtro per centralina (id)
     * @param  int[]|null  $rele         Filtro per relè (array di interi)
     * @param  int|null    $user_id      Filtro per utente
     */
    public function isHoliday(
        string $datetime,
        string $unique_name = null,
        int    $labkey_id   = null,
        array  $rele        = null,
        int    $user_id     = null
    ): array {
        $params = array_filter(compact('datetime', 'unique_name', 'labkey_id', 'rele', 'user_id'), fn($v) => $v !== null);
        $response = Http::withToken($this->getToken())->get($this->url . 'festivita/is_holiday', $params);
        $this->badRequest($response);
        if ($response->json('status') !== 'KO') {
            return $response->json('message');
        }
        return [];
    }

    /**
     * Crea una nuova festività.
     *
     * @param  string      $title              Titolo (obbligatorio)
     * @param  string      $start_datetime     Inizio  (Y-m-d H:i:s, obbligatorio)
     * @param  string      $end_datetime       Fine    (Y-m-d H:i:s, obbligatorio)
     * @param  string|null $description
     * @param  int|null    $recurring          0 o 1
     * @param  int|null    $active             0 o 1 (default 1)
     * @param  int|null    $parent_id
     * @param  string|null $notification_email
     * @param  int|null    $monday             0 o 1
     * @param  int|null    $tuesday            0 o 1
     * @param  int|null    $wednesday          0 o 1
     * @param  int|null    $thursday           0 o 1
     * @param  int|null    $friday             0 o 1
     * @param  int|null    $saturday           0 o 1
     * @param  int|null    $sunday             0 o 1
     * @param  array|null  $varcos             [['labkey_id'=>1,'rele'=>[1,2]], ...]
     * @param  int[]|null  $user_ids
     */
    public function create(
        string $title,
        string $start_datetime,
        string $end_datetime,
        string $description        = null,
        int    $recurring          = null,
        int    $active             = null,
        int    $parent_id          = null,
        string $notification_email = null,
        int    $monday             = null,
        int    $tuesday            = null,
        int    $wednesday          = null,
        int    $thursday           = null,
        int    $friday             = null,
        int    $saturday           = null,
        int    $sunday             = null,
        array  $varcos             = null,
        array  $user_ids           = null
    ): array {
        $params = array_filter(
            compact(
                'title', 'start_datetime', 'end_datetime', 'description',
                'recurring', 'active', 'parent_id', 'notification_email',
                'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday',
                'varcos', 'user_ids'
            ),
            fn($v) => $v !== null
        );
        $response = Http::withToken($this->getToken())->post($this->url . 'festivita/create', $params);
        $this->badRequest($response);
        if ($response->json('status') !== 'KO') {
            return $response->json('message');
        }
        return [];
    }

    /**
     * Aggiorna una festività esistente. Solo i parametri forniti vengono modificati.
     *
     * @param  int         $id                 ID festività (obbligatorio)
     * @param  string|null $title
     * @param  string|null $start_datetime     Y-m-d H:i:s
     * @param  string|null $end_datetime       Y-m-d H:i:s
     * @param  string|null $description
     * @param  int|null    $recurring          0 o 1
     * @param  int|null    $active             0 o 1
     * @param  int|null    $parent_id
     * @param  string|null $notification_email
     * @param  int|null    $monday             0 o 1
     * @param  int|null    $tuesday            0 o 1
     * @param  int|null    $wednesday          0 o 1
     * @param  int|null    $thursday           0 o 1
     * @param  int|null    $friday             0 o 1
     * @param  int|null    $saturday           0 o 1
     * @param  int|null    $sunday             0 o 1
     * @param  array|null  $varcos             [['labkey_id'=>1,'rele'=>[1,2]], ...]
     * @param  int[]|null  $user_ids
     */
    public function update(
        int    $id,
        string $title              = null,
        string $start_datetime     = null,
        string $end_datetime       = null,
        string $description        = null,
        int    $recurring          = null,
        int    $active             = null,
        int    $parent_id          = null,
        string $notification_email = null,
        int    $monday             = null,
        int    $tuesday            = null,
        int    $wednesday          = null,
        int    $thursday           = null,
        int    $friday             = null,
        int    $saturday           = null,
        int    $sunday             = null,
        array  $varcos             = null,
        array  $user_ids           = null
    ): array {
        $params = array_filter(
            compact(
                'id',
                'title', 'start_datetime', 'end_datetime', 'description',
                'recurring', 'active', 'parent_id', 'notification_email',
                'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday',
                'varcos', 'user_ids'
            ),
            fn($v) => $v !== null
        );
        $response = Http::withToken($this->getToken())->post($this->url . 'festivita/update', $params);
        $this->badRequest($response);
        if ($response->json('status') !== 'KO') {
            return $response->json('message') ?? [];
        }
        return [];
    }

    /**
     * Elimina una festività (e le sue pivot varcos/utenti).
     */
    public function delete(int $id): bool
    {
        $response = Http::withToken($this->getToken())->delete($this->url . 'festivita/delete', compact('id'));
        $this->badRequest($response);
        return $response->json('status') === 'OK';
    }
}