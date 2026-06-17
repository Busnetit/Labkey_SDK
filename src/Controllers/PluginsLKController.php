<?php

namespace Labkey\App\Controllers;

use Illuminate\Support\Facades\Http;

class PluginsLKController extends AuthorizeLKController
{
    /**
     * Restituisce le impostazioni di un plugin. I dati sono nella chiave 'settings'
     * e la loro struttura dipende dal plugin specifico.
     *
     * @param  string $plugin_name  Nome del plugin
     * @return mixed
     */
    public function getPluginsSettings(string $plugin_name)
    {
        $response = Http::withToken($this->getToken())->get($this->url . 'plugins/get_plugin_settings', get_defined_vars());
        $this->badRequest($response);
        if ($response->json('status') != 'KO') {
            return $response->json('settings');
        }
        return [];
    }
}
