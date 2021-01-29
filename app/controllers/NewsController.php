<?php
/**
 * Created by PhpStorm.
 * User: Fedor
 * Date: 28.01.2021
 * Time: 18:48
 */

namespace app\controllers;


use app\models\Main;
use Firebase\JWT\JWT;

class NewsController extends AppController
{




    public function getPostAction(){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        $model =new Main;

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $data = json_decode(file_get_contents("php://input"));
            $res = $this->tryJwtToken($data->jwt);
            try{
               $posts =  $model->findBySql("SELECT * FROM {$model->tablenews}");
                echo json_encode(array(
                   "ALL POSTS" => $posts
                ));
            }catch (\Exception $e){
                echo json_encode(array(
                   "message" => "Data Base error",
                   "text" => $e->getMessage()
                ));
            }
        }else {
            http_response_code(401);
            echo json_encode(array(
                "message" => "Wrong Request Method."
            ));
        }
    }


    public function postNewsAction(){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        $model =new Main;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents("php://input"));
            $res = $this->tryJwtToken($data->jwt);
            if(!empty($data->news)){
                try{
                    $model->insertBySql("INSERT INTO {$model->tablenews}(post_text,author)VALUES(?,?)",['1'=>$data->news,'2'=>$res->data->login]);
                    echo json_encode(array(
                        "message" => "News successfully saved.",
                    ));
                    die;
                }catch (\Exception $e){
                    echo json_encode(array(
                        "message" => "Data Base error",
                        "text" => $e->getMessage()
                    ));
                    die;
                }
            }else{
                echo json_encode(array(
                    "message" => ' The "news" field is missing'
                ));
            }
        } else {
            http_response_code(401);
            echo json_encode(array(
                "message" => "Wrong Request Method."
            ));
        }

    }
    public function editPostAction(){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        $model =new Main;
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            $data = json_decode(file_get_contents("php://input"));
            $res = $this->tryJwtToken($data->jwt);
            if(isset($data->id) && isset($data->text)){
                $info = $model->findBySql("SELECT * FROM {$model->tablenews} WHERE  id = ?",[$data->id ]);
                if($info == null){
                    echo json_encode(array(
                        "message" => "No data with this id",
                    ));
                    die;
                }else{
                    try{
                        $model->insertBySql("UPDATE {$model->tablenews} SET post_text =? WHERE id =? ",['1' => $data->text,'2'=>$data->id]);
                        echo json_encode(array(
                            "message" => "News has been edited.",
                        ));
                    }catch (\Exception $e){
                        echo json_encode(array(
                            "message" => "Data Base error",
                            "text" => $e->getMessage()
                        ));
                    }
                }
            }else{
                echo json_encode(array(
                    "message" => ' The "id" or "text" field is missing'
                ));
            }
        } else {
            http_response_code(401);
            echo json_encode(array(
                "message" => "Wrong Request Method."
            ));
        }
    }


    public function deletePostAction(){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        $model =new Main;
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            $data = json_decode(file_get_contents("php://input"));
            $res = $this->tryJwtToken($data->jwt);
            if(isset($data->id)){
                    $info = $model->findBySql("SELECT * FROM {$model->tablenews} WHERE  id = ?",[$data->id ]);
                if($info == null){
                    echo json_encode(array(
                        "message" => "News already deleted.",
                    ));
                    die;
                }
                $model->deleteBySql("DELETE FROM {$model->tablenews} WHERE id = ?",['1' => $data->id]);
                echo json_encode(array(
                    "message" => "News successfully deleted.",
                ));
                die;
            }else{
                echo json_encode(array(
                    "message" => ' The "id" field is missing'
                ));
            }
        } else {
            http_response_code(401);
            echo json_encode(array(
                "message" => "Wrong Request Method."
            ));
        }
    }
}