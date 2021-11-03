<?php

namespace app\controller;

use app\BaseController;
use Firebase\JWT\JWT as JwtUtil;

class Jwt extends BaseController
{
    public function createJwt()
    {
        $key = md5('test');//jwt的签发密钥，验证token的时候需要用到
        $time = time(); //当前时间
        $expire = $time + 7200; //过期时间,这里设置2小时
        $token = [
            'iss' => 'http://www.tp.com/', //签发者 可选
            'aud' => 'haha', //接收该jwt的一方 可选
            'iat' => $time, //签发时间
            'nbf' => $time, //(Not Before)：某个时间点后才能访问，比如设置time+30，表示当前时间30秒后才能使用
            'exp' => $expire, //过期时间
            'data' => [
                'user_id' => 1,
                'username' => '李小龙'
            ]
        ];

        $jwt = JwtUtil::encode($token, $key);
        return show($jwt, 200);
    }

    public function verifyJwt()
    {
        $jwt = input('jwt');
        $key = md5('test');
        try {
            $jwtauth = json_encode(JwtUtil::decode($jwt, $key, array("HS256")));
            $authinfo = json_decode($jwtauth, true);
            return show($authinfo, 200);
        } catch (\Firebase\JWT\SignatureInvalidException $e) { //签名不正确
            return show('签名不正确', 200);
        } catch (\Firebase\JWT\BeforeValidException $e) { // 签名在某个时间点之后才能用
            return show('签名在某个时间点之后才能用', 200);
        } catch (\Firebase\JWT\ExpiredException $e) { //token 过期
            return show('token过期', 200);
        } catch (\Exception $e) { //其他错误
            return show($e->getMessage(), 200);
        }
    }

}
