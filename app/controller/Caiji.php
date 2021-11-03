<?php

namespace app\controller;

use app\BaseController;
use QL\QueryList;

class Caiji extends BaseController
{
    public function index()
    {
        /*$data = QueryList::get('https://www.baidu.com/s?wd=QueryList', null, [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36',
                'Accept-Encoding' => 'gzip, deflate, br',
            ]
        ])->rules([
            'title' => ['h3', 'text'],
            'link' => ['h3>a', 'href']
        ])->range('.result')->queryData();

        dump($data);*/

        $ql = QueryList::get('https://www.baidu.com/s?wd=QueryList', null, [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36',
                'Accept-Encoding' => 'gzip, deflate, br',
            ]
        ]);
        $titles = $ql->find('h3>a')->texts(); //获取搜索结果标题列表
        $links = $ql->find('h3>a')->attrs('href'); //获取搜索结果链接列表
        dump($titles->all());
        dump($links->all());
    }
}