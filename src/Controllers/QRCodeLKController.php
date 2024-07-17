<?php

namespace Labkey\App\Controllers;


use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class QRCodeLKController extends Controller
{

    private $path_logo;
    private $path_font;
    private $site_url;

    public function __construct($path_logo, $path_font,$site_url = null)
    {
        $this->path_logo = $path_logo;
        $this->path_font = $path_font;
        $this->site_url = $site_url;
    }

    public function generateImage(string $code, $path_to_save,$first_name = null,$last_name = null,$email = null,$phone = null,$pin_access =  null)
    {

        $width = 400;
        $height = 700;
        $image = imagecreatetruecolor($width, $height);
        $bgColor = imagecolorallocate($image, 255, 255, 255); // Bianco
        $textColor = imagecolorallocate($image, 0, 0, 0); // Nero
        $ColoreTestoBlu = imagecolorallocate($image, 1, 113, 205); // Blu
        imagefill($image, 0, 0, $bgColor);
        $fontSize = 20;
        $x = 150;
        $y = 150;

        //region logo
        //todo create from png or jpg, it's depends on the image
        $logo = imagecreatefrompng($this->path_logo);
        $logoWidth = imagesx($logo);
        $logoHeight = imagesy($logo);
        imagecopy($image, $logo, (int)($width-$logoWidth)/2, 0, 0, 0, $logoWidth, $logoHeight);
        //endregion

        if(!empty($first_name)){
            // Calcolo della posizione del testo (centrato)
            $textBox = imagettfbbox($fontSize, 0, $this->path_font, $first_name);
            $textWidth = $textBox[2] - $textBox[0];
            $textHeight = $textBox[1] - $textBox[7];
            $y+= $textHeight;
            // Disegno del testo
            imagettftext($image, $fontSize, 0,(int)($width-$textWidth)/2,(int) $y, $textColor, $this->path_font, $first_name);
            $y+=20;
        }
        if(!empty($last_name)){
            // Calcolo della posizione del testo (centrato)
            $textBox = imagettfbbox($fontSize, 0, $this->path_font, $last_name);
            $textWidth = $textBox[2] - $textBox[0];
            $textHeight = $textBox[1] - $textBox[7];
            $y+= $textHeight;
            // Disegno del testo
            imagettftext($image, $fontSize, 0, (int)($width-$textWidth)/2,(int) $y, $textColor, $this->path_font, $last_name);
        }
        //region qrcode
        $data = base64_decode($this->generateQrCodeOnly($code));
        $im = imagecreatefromstring($data);
        $y+=20;
        imagecopy($image, $im, (int)(($width - imagesx($im))/2) , $y, 0, 0, imagesx($im), imagesy($im));
        //endregion
        $y+=imagesy($im)+45;

        if(!empty($phone)){
            $textBox = imagettfbbox($fontSize, 0, $this->path_font, $phone);
            $textWidth = $textBox[2] - $textBox[0];
            $textHeight = $textBox[1] - $textBox[7];
            $y+= $textHeight;
            imagettftext($image, $fontSize, 0, (int)($width-$textWidth)/2,(int) $y, $textColor, $this->path_font, $phone);
            $y+=10;
        }

        if(!empty($email)){
            $textBox = imagettfbbox($fontSize, 0, $this->path_font, $email);
            $textWidth = $textBox[2] - $textBox[0];
            $textHeight = $textBox[1] - $textBox[7];
            $y+= $textHeight;
            // Disegno del testo
            imagettftext($image, $fontSize, 0, (int)($width-$textWidth)/2,(int) $y, $textColor, $this->path_font, $email);
            $y+=15;
        }


        if(!empty($pin_access)){
            $textBox = imagettfbbox($fontSize, 0, $this->path_font, $pin_access);
            $textWidth = $textBox[2] - $textBox[0];
            $textHeight = $textBox[1] - $textBox[7];
            $y+= $textHeight;
            // Disegno del testo
            imagettftext($image, $fontSize, 0, (int)($width-$textWidth)/2,(int) $y, $textColor, $this->path_font, $pin_access);
            $y+=1;
        }


        if(!empty($this->site_url)){
            $textBox = imagettfbbox($fontSize, 0, $this->path_font, $this->site_url);
            $textWidth = $textBox[2] - $textBox[0];
            $textHeight = $textBox[1] - $textBox[7];
            $y+= $textHeight;
            // Disegno del testo
            imagettftext($image, $fontSize, 0, (int)($width-$textWidth)/2,690, $ColoreTestoBlu, $this->path_font, $this->site_url);
        }




        imagejpeg($image, $path_to_save);
    }
    private function generateQrCodeOnly(string $code)
    {
        $url = "https://image-charts.com/chart?chs=130x130&cht=qr&chl=".urlencode($code)."";
        $img = file_get_contents($url);
        return base64_encode($img);
    }

}