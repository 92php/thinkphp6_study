<?php

namespace app\controller;

use app\BaseController;
use think\facade\Db;
use app\model\Test as T;
use think\facade\Cache;
use think\facade\Env;
use app\model\User;

class Demo extends BaseController
{
    public function index()
    {
        //return "demo index";

        /*$data = [
            'name' => 'zhangsan',
            'age' => 20,
            'aihao' => [
                '乒乓球',
                '游泳'
            ]
        ];

        return json($data);*/

        //$d = $this->request->param();
        //dump($d);

        /*$data = [
            'name' => 'zhangsan',
            'age' => 20,
            'aihao' => [
                '乒乓球',
                '游泳'
            ]
        ];
        return show($data,200);*/

        //return config('status.data_controller');

        //$res = Db::table('test')->where("id",1)->find();
        //dump($res);

        /*$res = Db::table('test')
            ->order('id','desc')
            ->limit(2,2)
            ->page(2,2)
            ->where("id",'>',2)
            ->select();
        dump($res);*/

        //$d = Db::table('test')->where("id",1)->fetchSql()->find();
        //dump($d);

        //Db::table('test')->where("id",">",1)->select();
        //echo Db::getLastSql();exit();

        /*$data = [
            'name' => "nihao",
            'time' => time()
        ];
        $res = Db::table('test')->insert($data);
        dump($res);*/

        //$res = Db::table('test')->where(["id"=>1])->delete();
        //dump($res);

        //$res = Db::table('test')->where(["id"=>2])->update(['name'=>'hahahhahahah']);
        //dump($res);

        //$res = T::find(['id'=>2])->toArray();
        //dump($res);

        //Cache::store('redis')->set('name','value',3600);
        //$res = Cache::store('redis')->get('name');
        //dump($res);


        //$res = Env::get('database.username');
        //dump($res);


        //dump(PUBLIC_PATH);

        /*$info = Db::connect('mongo')
            ->table('test')
            ->where('name','lisi')
            ->select();
        dump($info);*/

        /*$user = User::find(1);
        echo $user->create_time;
        echo $user->name;
        echo "-------------";
        echo $user['create_time'];
        echo $user['name'];
        die;*/

        /*$user = new User();
        $user->getDataById(1);
        die;*/

        /*$user           = new User;
        $user->name     = 'thinkphp';
        $user->email    = 'thinkphp@qq.com';
        $user->save();
        die;*/

        /*$user = new User;
        $user->save([
                'name'  =>  'thinkphp1',
                'email' =>  'thinkphp1@qq.com'
        ]);
        die;*/

        /*$user           = new User;
        $user->name     = 'thinkphp3';
        $user->email    = 'thinkphp@qq.com3';
        $user->save();
        // 获取自增ID
        echo $user->id;
        die;*/


        /*$user = new User;
        $list = [
            ['name'=>'thinkphp333','email'=>'thinkphp@qq.com'],
            ['name'=>'onethink333','email'=>'onethink@qq.com']
        ];
        $user->saveAll($list);
        die;*/

        /*$user = User::create([
            'name'  =>  'thinkphp',
            'email' =>  'thinkphp@qq.com'
        ], ['name', 'email']);
        echo $user->name;
        echo $user->email;
        echo $user->id; // 获取自增ID
        die;*/



    }
}