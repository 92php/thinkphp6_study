<?php

namespace app\controller;

use app\BaseController;
use Mpdf\Mpdf;

class Pdf extends BaseController
{
    public function index()
    {
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML('<h1>Hello world!</h1>');
        $save_path = "test.pdf";
        $mpdf->Output($save_path,'D');
    }

}