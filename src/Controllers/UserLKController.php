<?php

namespace Labkey\App\Controllers;

use Illuminate\Support\Facades\Http;

class UserLKController extends AuthorizeLKController{



      /**
     * @param string $name
     * @param string $surname
     * @param string $email
     * @param string $phone
     * @param string $prefix
     *
     * @return string
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function create(string $name, string $surname, string $email='', string $phone='', string $prefix='',array $tags = []) :array{
        $response=Http::withToken($this->getToken())->put($this->url.'adduser',get_defined_vars());
        $this->badRequest($response);
        return $response->json('message');
    }

    /**
     * @param string   $user_id
     * @param string   $tag
     * @param string   $email
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return array
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function getUser(string $user_id='',string $first_name = '', string $last_name = '', string $tag='', string $email='', int|null $limit=null,  int|null $offset=null): array{
        $response=Http::withToken($this->getToken())->get($this->url.'getusers',get_defined_vars());
        if($response->status() !== 404 ){ //NON CAMBIARE MAI QUESTO CODICE DI ERRORE IN QUANTO è HARDCODATO PER QUANTO RIGUARDA LE INTEGRAZIONI per maggiori info vai a vedere nel pannello la funzione getUsersAllV2
            $this->badRequest($response);
        }
        if($response->json('status') != 'KO'){
            return $response->json('message');
        }
        return [];
    }

    public function updateUser(int $user_id, string|null $name = '',string|null $surname = '', string|null $tag = '',string|null $email = '', array|null $tags = []){
        $response = Http::withToken($this->getToken())->PUT($this->url.'updateuser',get_defined_vars());
        $this->badRequest($response);
        if($response->json('status') != 'KO'){
            return $response->json('message');
        }
        return [];
    }
    /**
     * @param string $user_id
     * @param string $nfc_key_id
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function addKeyUser(string $user_id, string $nfc_key_id){
        $response=Http::withToken($this->getToken())->put($this->url.'addkey2user',get_defined_vars());
        $this->badRequest($response);
        if($response->json('status') != 'KO'){
            return $response->json('content');
        }
        return[];
    }

    /**
     * @param int $user_id
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function deleteUser(int $user_id){
        $response=Http::withToken($this->getToken())->delete($this->url.'deleteuser',get_defined_vars());
        $this->badRequest($response);
        if($response->json('status') != 'OK'){
            return $response->json('message');
        }
        return[];
    }

    public function grantAccess(int $user_id,
                                int $key_id,
                                string $unique_name,
                                string $tt='si',
                                string $tv ='si',
                                string|null $datei=null,
                                string|null $datef=null,
                                string|null $houri=null,
                                string|null $hourf=null,
                                string|null $mo ='si',
                                string|null $tu ='si',
                                string|null $we ='si',
                                string|null $th ='si',
                                string|null $fr ='si',
                                string|null $sa ='si',
                                string|null $su ='si',
                                string|null $command_device_id=null,
                                array|null $id_rele=null):array
    {

        $data[$unique_name]=[
            'tt'=>$tt,
            'tv'=>$tv
        ];
        if(!empty($datei)){
            $data[$unique_name]['datei']=$datei;
        }
        if(!empty($datef)){
            $data[$unique_name]['datef']=$datef;
        }
        if(!empty($houri)){
            $data[$unique_name]['houri']=$houri;
        }
        if(!empty($hourf)){
            $data[$unique_name]['hourf']=$hourf;
        }
        if(!empty($mo)){
            $data[$unique_name]['mo']=$mo;
        }
        if(!empty($tu)){
            $data[$unique_name]['tu']=$tu;
        }
        if(!empty($we)){
            $data[$unique_name]['we']=$we;
        }
        if(!empty($th)){
            $data[$unique_name]['th']=$th;
        }
        if(!empty($fr)){
            $data[$unique_name]['fr']=$fr;
        }
        if(!empty($sa)){
            $data[$unique_name]['sa']=$sa;
        }
        if(!empty($su)){
            $data[$unique_name]['su']=$su;
        }
        if(!empty($command_device_id)){
            $data[$unique_name]['command_device_id']=$command_device_id;
        }
        if(!empty($id_rele)){
            $data[$unique_name]['id_rele']=$id_rele;
        }

        return $this->doGrantAccess($user_id, json_encode($data), $key_id);

    }

    public function doGrantAccess(int $user_id, string $data, int $key_id):array{
        // dd(get_defined_vars());
        $response=Http::withToken($this->getToken())->post($this->url.'grantaccess',get_defined_vars());
        dd($response->body());
        $this->badRequest($response);
        if($response->json('status') != 'OK'){
            return $response->json('insert');
        }
        return[];
    }
}