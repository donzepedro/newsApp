<?php

namespace app\controllers;
use Firebase\JWT\JWT;

use vendor\core\base\Controller;
use app\models\Main;

class AppController extends Controller {

    public function logincheck()
    {
        $model = new Main;
        require JWTCONF;
        if (!empty($_COOKIE)){
            $JWT = new JWT;
            $decode = $JWT->decode($_COOKIE['trust'],$key, array('HS256'));
            $usname=$model->findBySql("SELECT `name` FROM `user` WHERE `id` = ?",[$decode->data->id]);
            $logdata=['id' => $decode->data->id,'name' => $usname[0]['name']];
            return $logdata;
        }else{
            return false;
        }
    }

    public function tryJwtToken($token){
        try {

            $jwtobj = new JWT();
            require JWTCONF;
            $requestinfo = $jwtobj->decode($token,$key,array('HS256'));
            return $requestinfo;
        }catch (\Exception $e){
            http_response_code(401);
            echo json_encode(array(
                "message" => "Jwt token is wrong.",
                "text" => $e->getMessage()
            ));
            exit;
        }
    }

}