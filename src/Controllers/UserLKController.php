<?php

namespace Labkey\App\Controllers;

use Illuminate\Support\Facades\Http;

class UserLKController extends AuthorizeLKController
{


    /**
     * @param string $name
     * @param string $surname
     * @param string $email
     * @param string $phone
     * @param string $prefix
     * @param array $tags
     * @param array $fields
     * @param int|null $status
     * @return array
     */
    public function create(
        string $name,
        string $surname,
        string $email = '',
        string $phone = '',
        string $prefix = '',
        array $tags = [],
        array $fields = [],
        int $status = null,
        int|null $price_list_id = null
    ): array {
        $response = Http::withToken($this->getToken())->put($this->url . 'adduser', get_defined_vars());
        // dd($response->body());
        $this->badRequest($response);
        return $response->json('message');
    }

    /**
     * @param string $user_id
     * @param string $first_name
     * @param string $last_name
     * @param string $tag
     * @param string $email
     * @param string $phone
     * @param int|null $limit
     * @param int|null $offset
     * @param bool $getGrantInfo
     * @param int|null $created_at_from
     * @param int|null $created_at_to
     * @return array
     */
    public function getUser(
        string $user_id = '',
        string $first_name = '',
        string $last_name = '',
        string $tag = '',
        string $email = '',
        string $phone = '',
        int|null $limit = null,
        int|null $offset = null,
        bool $getGrantInfo = false,
        int|null $created_at_from=null,
        int|null $created_at_to=null,
    ): array {
        $response = Http::withToken($this->getToken())->get($this->url . 'getusers', get_defined_vars());
        if ($response->status(
            ) !== 404) { //NON CAMBIARE MAI QUESTO CODICE DI ERRORE IN QUANTO è HARDCODATO PER QUANTO RIGUARDA LE INTEGRAZIONI per maggiori info vai a vedere nel pannello la funzione getUsersAllV2
            $this->badRequest($response);
        }
        if ($response->json('status') != 'KO') {
            return $response->json('message');
        }
        return [];
    }

    /**
     * @param int $user_id
     * @param string|null $name
     * @param string|null $surname
     * @param string|null $email
     * @param string|null $phone
     * @param string|null $prefix
     * @param array|null $tags
     * @param array $fields
     * @param int|null $status
     * @return array
     */
    public function updateUser(
        int $user_id,
        string|null $name = '',
        string|null $surname = '',
        string|null $email = '',
        string|null $phone = '',
        string|null $prefix = '',
        array|null $tags = [],
        array $fields = [],
        int $status = null
    ) {
        $response = Http::withToken($this->getToken())->PUT($this->url . 'updateuser', get_defined_vars());
        $this->badRequest($response);
        if ($response->json('status') != 'KO') {
            return $response->json('message');
        }
        return [];
    }

    /**
     * @param string $user_id
     * @param string $nfc_key_id
     *
     * @return array
     */
    public function addKeyUser(string $user_id, string $nfc_key_id)
    {
        $response = Http::withToken($this->getToken())->put($this->url . 'addkey2user', get_defined_vars());
        $this->badRequest($response);
        if ($response->json('status') != 'KO') {
            return $response->json('content');
        }
        return [];
    }

    /**
     * @param int $user_id
     *
     * @return array
     */
    public function deleteUser(int $user_id)
    {
        $response = Http::withToken($this->getToken())->delete($this->url . 'deleteuser', get_defined_vars());
        $this->badRequest($response);
        if ($response->json('status') != 'OK') {
            return $response->json('message');
        }
        return [];
    }

    /**
     * @param int $user_id
     * @param int $key_id
     * @param string $unique_name
     * @param string $tt
     * @param string $tv
     * @param string|null $datei
     * @param string|null $datef
     * @param string|null $houri
     * @param string|null $hourf
     * @param string|null $mo
     * @param string|null $tu
     * @param string|null $we
     * @param string|null $th
     * @param string|null $fr
     * @param string|null $sa
     * @param string|null $su
     * @param string|null $command_device_id
     * @param array|null $id_rele
     * @return array
     */
    public function grantAccess(
        int $user_id,
        int $key_id,
        string $unique_name,
        string $tt = 'si',
        string $tv = 'si',
        string|null $datei = null,
        string|null $datef = null,
        string|null $houri = null,
        string|null $hourf = null,
        string|null $mo = 'si',
        string|null $tu = 'si',
        string|null $we = 'si',
        string|null $th = 'si',
        string|null $fr = 'si',
        string|null $sa = 'si',
        string|null $su = 'si',
        string|null $command_device_id = null,
        array|null $id_rele = null,
        string|null $technology = null
    ): array {
        $data[$unique_name] = [
            'tt' => $tt,
            'tv' => $tv
        ];
        if (!empty($datei)) {
            $data[$unique_name]['datei'] = $datei;
        }
        if (!empty($datef)) {
            $data[$unique_name]['datef'] = $datef;
        }
        if (!empty($houri)) {
            $data[$unique_name]['houri'] = $houri;
        }
        if (!empty($hourf)) {
            $data[$unique_name]['hourf'] = $hourf;
        }
        if (!empty($mo)) {
            $data[$unique_name]['mo'] = $mo;
        }
        if (!empty($tu)) {
            $data[$unique_name]['tu'] = $tu;
        }
        if (!empty($we)) {
            $data[$unique_name]['we'] = $we;
        }
        if (!empty($th)) {
            $data[$unique_name]['th'] = $th;
        }
        if (!empty($fr)) {
            $data[$unique_name]['fr'] = $fr;
        }
        if (!empty($sa)) {
            $data[$unique_name]['sa'] = $sa;
        }
        if (!empty($su)) {
            $data[$unique_name]['su'] = $su;
        }
        if (!empty($command_device_id)) {
            $data[$unique_name]['command_device_id'] = $command_device_id;
        }
        if (!empty($id_rele)) {
            $data[$unique_name]['id_rele'] = $id_rele;
        }
        if (!empty($technology)) {
            $data[$unique_name]['technology'] = $technology;
        }

        return $this->doGrantAccess($user_id, json_encode($data), $key_id);
    }

    /**
     * @param int $user_id
     * @param string $data
     * @param int $key_id
     * @return array
     */
    public function doGrantAccess(int $user_id, string $data, int $key_id): array
    {
        $response = Http::withToken($this->getToken())->post($this->url . 'grantaccess', get_defined_vars());
        $this->badRequest($response);
        if ($response->json('status') != 'OK') {
            return $response->json('insert');
        }
        return [];
    }

    /**
     * @param int $user_id
     * @param int $status
     * @return true
     */
    public function changeStatus(int $user_id, int $status): bool
    {
        $response = Http::withToken($this->getToken())->post($this->url . 'users/changeStatus', get_defined_vars());
        $this->badRequest($response);
        if ($response->json('status') != 'OK') {
            return $response->json('message');
        }
        return true;
    }

    /**
     * @param int $user_id
     * @return mixed
     */
    public function getStatus(int $user_id): mixed
    {
        $response = Http::withToken($this->getToken())->get($this->url . 'users/getStatus', get_defined_vars());
        $this->badRequest($response);
        return $response->json('message');
    }


    /**
     * @param int $user_id
     * @param bool $send_permissions_list
     * @param bool $send_fast_url
     * @param bool $send_qr_code
     * @param bool $show_sender_name
     * @param string|null $message
     * @param string[]|null $cc_emails
     * @param string|null $language
     * @param string|null $alternative_to
     * @param string|null $template
     * @param string|null $template_data
     * @param string|null $subject
     * @return mixed
     */
    public function sendEmail(
        int $user_id,
        bool $send_permissions_list = true,
        bool $send_fast_url = true,
        bool $send_qr_code = true,
        bool $show_sender_name = true,
        string|null $message = null,
        string|null $cc_emails = null,
        string|null $language = null,
        string|null $alternative_to = null,
        string|null $template = null,
        string|null $template_data = null,
        string|null $subject = null
    ): string {
        $values = get_defined_vars();
        $values['operator_email'] = $this->email;
        $response = Http::withToken($this->getToken())->post($this->url . 'sendemail', $values);
        $this->badRequest($response);
        return $response->json('message');
    }

    /**
     * @param int $id_user
     * @param string $message
     * @param string|null $phone
     * @return string
     */
    public function sendSMS(
        int $id_user,
        string $message,
        string|null $phone
    ): string {
        $values = get_defined_vars();
        $response = Http::withToken($this->getToken())->post($this->url . 'sendsms', $values);
        $this->badRequest($response);
        return $response->json('message');
    }


    /**
     * @param int $user_id
     * @param int $involved_associations
     * @return array
     */
    public function getGrantInfo(int $involved_associations): array
    {
        $response = Http::withToken($this->getToken())->post($this->url . 'getGrantInfo', get_defined_vars());
        $this->badRequest($response);
        if ($response->json('status') === 'OK') {
            $response= $response->json();
            unset($response['status']);
            return $response;
        }
        return [];
    }


    public function dropAccess(int|array $involved_associations): array
    {
        $payload = ['involved_associations' => is_array($involved_associations) ? $involved_associations : [$involved_associations]];
        $response = Http::withToken($this->getToken())->delete($this->url . 'dropaccess', $payload);
        $this->badRequest($response);
        if ($response->json('status') === 'OK') {
            $response = $response->json();
            unset($response['status']);
            return $response;
        }
        return [];
    }

    /**
     * @param int $user_id
     * @param bool|null $image
     * @param bool|null $with_background
     * @param bool|null $url
     * @return array
     */
    public function getQrCode(
        int $user_id,
        bool|null $image = false,
        bool|null $with_background = false,
        bool|null $url = false
    ): array {
        $response = Http::withToken($this->getToken())->post($this->url . 'getqrcode', get_defined_vars());
        $this->badRequest($response);
        if ($response->json('status') === 'OK') {
            $result = $response->json();
            unset($result['status']);
            return $result;
        }
        return [];
    }
}