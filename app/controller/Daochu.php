<?php

namespace app\controller;

use app\BaseController;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;

class Daochu extends BaseController
{
    public function index()
    {
        $cells = [
            WriterEntityFactory::createCell('序号'),
            WriterEntityFactory::createCell('姓名'),
            WriterEntityFactory::createCell('工号'),
        ];
        $writer = WriterEntityFactory::createXLSXWriter()->openToBrowser("测试.xlsx");
        $singleRow = WriterEntityFactory::createRow($cells);
        $writer->addRow($singleRow);
        $values = ['1','maro','G-1008'];
        $rowFromValues = WriterEntityFactory::createRowFromArray($values);
        $writer->addRow($rowFromValues);

        $writer->close();

    }















}