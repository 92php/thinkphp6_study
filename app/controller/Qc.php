<?php

namespace app\controller;

use app\BaseController;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

class Qc extends BaseController
{
    public function index()
    {
        $writer = new PngWriter();

        // Create QR code
        $qrCode = QrCode::create('https://www.baidu.com')
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
            ->setSize(400)
            ->setMargin(50)
            ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

        /*// Create generic logo
        $logo = Logo::create(__DIR__.'/assets/symfony.png')
            ->setResizeToWidth(50);

        // Create generic label
        $label = Label::create('Label')
            ->setTextColor(new Color(255, 0, 0));*/

        $result = $writer->write($qrCode);
        $dataUri = $result->getDataUri();
        $imgStr = preg_replace('#^data:image/[^;]+;base64,#', '', $dataUri);
        $data = base64_decode($imgStr);
        $im = imagecreatefromstring($data);
        if($im){
            header('Content-type:image/png');
            imagepng($im);
            imagedestroy($im);
            die;
        }

    }
}