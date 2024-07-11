<?php
namespace Labkey\App\Controllers;

use Labkey\App\Controllers\Controller as Controller;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;


class AuthorizeLKController extends Controller { 

    protected string $secret_key;
    protected string $email;
    protected string $password;
    protected string $url;
    public  static $barer=null;
    public static $barer_last=0;

    public function __construct() {
       
        $this->secret_key = getenv('LABKEY_API_SECRET_KEY');
        $this->email = getenv('LABKEY_API_EMAIL');
        $this->password = getenv('LABKEY_API_PASSWORD');  
        $this->url = getenv('LABKEY_API_URI');
        
        //TODO etc ....
        if(empty($this->secret_key) || empty($this->email)){
           throw new \Exception("Configurazion is not valid, you have to set: LABKEY_API_SECRET_KEY, LABKEY_API_EMAIL ... as env");  
        }
    }

     /**
     * @throws RequestException
     */
    protected function getToken() {
        if(empty(self::$barer) || (time()-self::$barer_last)>(5*60)){
            $client = new \GuzzleHttp\Client();
            $response=Http::asForm()->withUserAgent('SDK-Labkey')->post($this->url.'authorize',['email'=>$this->email,'password'=>$this->password, 'secret_key'=>$this->secret_key]);
            $this->badRequest($response);
            self::$barer= $response->json('token');
            self::$barer_last=time();
            dump('refresho token barer _  '. self::$barer_last);
        }
        return self::$barer;
    }

}