<?php

namespace Labkey\App\Controllers;

use Illuminate\Support\Facades\Http;

class CheckinLKController extends AuthorizeLKController
{
    /**
     * Invia un OTP via SMS per il check-in.
     *
     * @param  string $otp           Codice OTP da inviare
     * @param  string $phone_number  Numero di telefono del destinatario
     * @return array  message + message_id
     */
    public function sendOtp(string $otp, string $phone_number): array
    {
        $response = Http::withToken($this->getToken())->post($this->url . 'checkin/send_otp_checkin', get_defined_vars());
        $this->badRequest($response);
        if ($response->json('status') === 'OK') {
            $data = $response->json();
            unset($data['status']);
            return $data;
        }
        return [];
    }

    /**
     * Recupera i dati del check-in tramite hash. Se il check-in richiede un pagamento non ancora
     * effettuato, la risposta include anche payment_url e company.
     *
     * @param  string      $hash         Hash del check-in
     * @param  string|null $success_url  URL di ritorno in caso di pagamento riuscito (opzionale)
     * @param  string|null $cancel_url   URL di ritorno in caso di annullamento (opzionale)
     * @return array
     */
    public function getCheckInData(string $hash, string|null $success_url = null, string|null $cancel_url = null): array
    {
        $response = Http::withToken($this->getToken())->get($this->url . 'checkin/retrive_check_in', get_defined_vars());
        $this->badRequest($response);
        if ($response->json('status') === 'OK') {
            $data = $response->json();
            unset($data['status']);
            return $data;
        }
        return [];
    }

    /**
     * Restituisce l'id dell'appartamento associato al check-in (per gli Alloggiati).
     *
     * @param  string $hash  Hash del check-in
     * @return array  { id_appartamento }
     */
    public function getIdAppartamentoAlloggiati(string $hash): array
    {
        $response = Http::withToken($this->getToken())->get($this->url . 'checkin/get_id_appartamento_alloggiati', get_defined_vars());
        $this->badRequest($response);
        if ($response->json('status') !== 'KO') {
            return $response->json('message');
        }
        return [];
    }

    /**
     * Restituisce il nome della struttura associata al check-in.
     *
     * @param  string $checkin_hash  Hash del check-in (parametro: checkin_hash)
     * @return array  { nome_struttura }
     */
    public function getNomeStruttura(string $checkin_hash): array
    {
        $response = Http::withToken($this->getToken())->get($this->url . 'checkin/getNomeStuttura', get_defined_vars());
        $this->badRequest($response);
        if ($response->json('status') !== 'KO') {
            return $response->json('message');
        }
        return [];
    }

    /**
     * Genera/recupera l'URL di pagamento per un check-in. Se già pagato, la risposta contiene
     * already_paid=true; altrimenti url e company.
     *
     * @param  string $hash         Hash del check-in
     * @param  string $success_url  URL di ritorno in caso di pagamento riuscito
     * @param  string $cancel_url   URL di ritorno in caso di annullamento
     * @return array
     */
    public function getPaymentUrl(string $hash, string $success_url, string $cancel_url): array
    {
        $response = Http::withToken($this->getToken())->post($this->url . 'checkin/retrive_payment_url', get_defined_vars());
        $this->badRequest($response);
        if ($response->json('status') === 'OK') {
            $data = $response->json();
            unset($data['status']);
            return $data;
        }
        return [];
    }

    /**
     * Recupera i dati di pagamento di un check-in.
     *
     * @param  string $plugin  Tipo di pagamento: 'check_in' oppure 'payment_url'
     * @param  string $hash    Hash del check-in / payment url
     * @return array  Oggetto pagamento
     */
    public function getPaymentData(string $plugin, string $hash): array
    {
        $response = Http::withToken($this->getToken())->post($this->url . 'checkin/retrive_payment_data', get_defined_vars());
        $this->badRequest($response);
        if ($response->json('status') !== 'KO') {
            return $response->json('message');
        }
        return [];
    }

    /**
     * Salva un check-in verificato. Il payload viene inviato come JSON nel corpo della richiesta.
     *
     * @param  string $check_in_hash      Hash del check-in
     * @param  array  $main_guest_data    Dati dell'ospite principale (può contenere reservation_type e guests_count)
     * @param  array  $guests_data        Array degli ospiti
     * @param  string $verified_with      Metodo di verifica utilizzato
     * @param  array  $documents_data     Array dei documenti
     * @param  string $verification_hash  Hash di verifica
     * @return bool   true se il check-in è stato salvato
     */
    public function saveVerifiedCheckin(
        string $check_in_hash,
        array  $main_guest_data,
        array  $guests_data,
        string $verified_with,
        array  $documents_data,
        string $verification_hash
    ): bool {
        $response = Http::withToken($this->getToken())->post($this->url . 'checkin/save_verified_checkin', get_defined_vars());
        $this->badRequest($response);
        return $response->json('status') === 'OK';
    }
}
