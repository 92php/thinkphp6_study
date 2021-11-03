<?php

namespace app\controller;

use app\BaseController;
use Fukuball\Jieba\Finalseg;
use Fukuball\Jieba\Jieba;

class fenci extends BaseController
{
    public function jieba()
    {
        ini_set('memory_limit', '1024M');
        Jieba::init();
        Finalseg::init();
        echo "<pre>";
        //$list = Jieba::cut("结巴分词做中国最好的分词");
        //$list = Jieba::cut("结巴分词做中国最好的分词",true); #全模式
        //$list = Jieba::cut("结巴分词做中国最好的分词",false);#默认精确模式
        $list = Jieba::cutForSearch("小明硕士毕业于中国科学院计算所，后在日本京都大学深造"); #搜索引擎模式
        var_dump($list);
        echo "</pre>";
    }
}