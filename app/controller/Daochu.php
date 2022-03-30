<?php

namespace app\controller;

use app\BaseController;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;



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


    public function insert()
    {
        /*$filePath = 'C:/Users/China/Desktop/公司/行报价信息表(1)/行报价信息表-国网湖南省电力有限公司2020年第二次物资协议库存招标采购/行信息报价基础数据表——金具-10kV及以下.xlsx';


        $path =$filePath;
        $type = pathinfo($path);
        $type = strtolower($type["extension"]);
        $type = ($type === 'xlsx')?Type::XLSX:Type::CSV;
        $reader = ReaderFactory::create($type);
        $reader->setShouldFormatDates(true);
        if($type === 'csv'){$reader->setEncoding('GB2312');}
        $reader->open($path);
        $iterator = $reader->getSheetIterator();
        $iterator->rewind();
        $sheet1 = $iterator->current();
        $rowIter = $sheet1->getRowIterator();
        $data =[];
        foreach ($rowIter as $rowIndex => $row) {
            $data[] = $row;
        }
        $reader->close();*/
    }

    // xlswriter 导入导出
    public function test()
    {
        /*$config = [
            'path' => APP_PATH.'/public/test/' // xlsx文件保存路径
        ];

        $excel  = new \Vtiful\Kernel\Excel($config);

        // fileName 会自动创建一个工作表，你可以自定义该工作表名称，工作表名称为可选参数
        $filePath = $excel->fileName('tutorial01.xlsx', 'sheet1')
            ->header(['Item', 'Cost'])
            ->data([
                ['Rent', 1000],
                ['Gas',  100],
                ['Food', 300],
                ['Gym',  50],
            ])
            ->output();*/

        $config   = ['path' => APP_PATH.'/public/test/'];
        $excel    = new \Vtiful\Kernel\Excel($config);

        // 读取测试文件
        $data = $excel->openFile('tutorial01.xlsx')
            ->openSheet()
            ->getSheetData();

        var_dump($data); // [['Item', 'Cost']]

    }















}