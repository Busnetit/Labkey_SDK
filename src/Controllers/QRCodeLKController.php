<?php

namespace Labkey\App\Controllers;


use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class QRCodeLKController extends Controller
{

    private $path_background;
    private $path_font;

    public function __construct($path_background, $path_font)
    {
        $this->path_background = $path_background;
        $this->path_font = $path_font;
    }

    public function generateImage(string $code, $path_to_save,$first_name = null,$last_name = null,$email = null,$phone = null)
    {
        if (!function_exists('imagecreatefromjpeg')) {
            throw new \Exception("Function imagecreatefromjpeg() does not exists, you have to install GD Library on your system.");
            exit;
        }
        $photo = imagecreatefromjpeg($this->path_background);
        imagealphablending($photo, false);
        if (!function_exists('imagecreatefromstring')) {
            throw new \Exception("Function imagecreatefromstring() does not exists, you have to install GD Library on your system.");
            exit;
        }

        $data = base64_decode($this->generateQrCodeOnly($code));
        $im = imagecreatefromstring($data);
        $x = imagesx($im);
        imagecopy($photo, $im, (500 / 2) - ($x / 2), 280, 0, 0, 360, 360);

        $ColoreTestoBlack = imagecolorallocate($photo, 128,128,128);
        $ColoreTestoBlu = imagecolorallocate($photo, 1, 113, 205);
        $font_size = 15;


        if(!empty($first_name)){
            $bbox = imagettfbbox($font_size, 0, $this->path_font,$first_name);
            $textWidth = $bbox[2] - $bbox[0];
            $x = (500 / 2) - ($textWidth / 2);
            imagettftext($photo, $font_size, 0, $x, 240, $ColoreTestoBlack, $this->path_font,$first_name);
        }
        if(!empty($last_name)){
            $bbox = imagettfbbox($font_size, 0, $this->path_font,$last_name);
            $textWidth = $bbox[2] - $bbox[0];
            $x = (500 / 2) - ($textWidth / 2);
            imagettftext($photo, $font_size, 0, $x, 290, $ColoreTestoBlack, $this->path_font,$last_name);

        }
        if(!empty($phone)){
            $bbox = imagettfbbox($font_size, 0, $this->path_font,$phone);
            $textWidth = $bbox[2] - $bbox[0];
            $x = (500 / 2) - ($textWidth / 2);
            imagettftext($photo, $font_size, 0, $x, 740, $ColoreTestoBlack, $this->path_font,$phone);
        }

        if(!empty($email)){
            $bbox = imagettfbbox($font_size, 0, $this->path_font,$email);
            $textWidth = $bbox[2] - $bbox[0];
            $x = (500 / 2) - ($textWidth / 2);
            imagettftext($photo, $font_size, 0, $x - 195, 690, $ColoreTestoBlack, $this->path_font,$email);
        }

        imagejpeg($photo, $path_to_save);
        return $path_to_save;
    }

    private function generateQrCodeOnly(string $code)
    {
        $url = "https://image-charts.com/chart?chs=250x250&cht=qr&chl=".urlencode($code)."";
        $img = file_get_contents($url);
        return base64_encode($img);
    }

}