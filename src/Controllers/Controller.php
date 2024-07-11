<?php

namespace Labkey\App\Controllers;

//use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
//use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Client\RequestException;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use PHPUnit\Logging\Exception;
use  Illuminate\Http\Client\Response;


class Controller extends BaseController
{
    //use AuthorizesRequests, ValidatesRequests;
    
    protected string $url;
    
    protected function missingEnvValue($param){
        if(empty(getenv($param))){
            throw new Exception('Missing '.$param.' in .env file');
        }
    }
    
    /**
     * @throws RequestException
     */
    protected function badRequest(Response $response){
        $response->throwIfServerError();
        $response->throwIfClientError();
    }
    
    
    protected function test(): string {
        $response=Http::get($this->url);
        $this->badRequest($response);
        return $response->body();
    }
    
    
    public function __construct() {
        $this->missingEnvValue('LABKEY_API_ENDPOINT');
        $this->url=env('LABKEY_API_ENDPOINT', 'demo.manage.labkey.io').'/api/v2/';
        if(!Str::startsWith($this->url,['https://'])){
            $this->url='https://'.$this->url;
        }
        Str::remove(['www.','www','http://'],$this->url);
    }
}