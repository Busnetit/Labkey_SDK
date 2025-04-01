<?php

namespace Labkey\App\Controllers;

use Illuminate\Support\Facades\Http;

class CustomFieldsLKController extends AuthorizeLKController
{

   public function getCustomFileds(int|null $id=null, int|null $limit=null, int|null $offset=null){
       $url=$this->url.'customfields';
       if(!empty($id)){
           $url.='/'.$id;
       }
       $response=Http::withToken($this->getToken())->get($url,get_defined_vars());
       $this->badRequest($response);
       return $response->json('message');
   }
}