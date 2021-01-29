<?php
/**
 * Created by PhpStorm.
 * User: SRT
 * Date: 16.02.2019
 * Time: 12:21
 */

namespace app\controllers;
use app\models\Main;
use Firebase\JWT\JWT;
use RedBeanPHP\Logger\RDefault\Debug;
use function Sodium\compare;

class GetjwtController extends AppController
{
    public $view='none';
    public function getTokenAction()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $data = json_decode(file_get_contents("php://input"));
//            $isquery=isset($data->query) ? $data->query : "";
            if ($data->query == 'getjwt') {
                $login = clean_data($data->login);
                if (Checkemail($login)) {
                    $pass = passencode(clean_data($data->password));
                    $model = new Main;
                    $jwt = new JWT;
                    if ($data = $model->findBySql("SELECT `id`, `login` FROM {$model->tableuser} WHERE `login` = ? AND `passwords` = ?", [$login, $pass])) {
                        try {
                            require JWTCONF;
                            $token = array(
                                "iss" => $iss,
                                "aud" => $aud,
                                "iat" => $iat,
                                "nbf" => $nbf,
                                "data" => array(
                                    "id" => $data[0]['id'],
                                    "login" => $data[0]['login'],
                                )
                            );

                            $token = $jwt->encode($token, $key);
                            http_response_code(200);
                            echo json_encode(
                                array(
                                    "message" => "Successful login",
                                    "jwt" => $token
                                )
                            );
                        } catch (\Exception $e) {
                            http_response_code(401);
                            // tell the user access denied  & show error message
                            echo json_encode(array(
                                "message" => "Access denied.",
                                "error" => $e->getMessage()
                            ));
                        }
                    } else {

                        // set response code
                        http_response_code(401);

                        // tell the user access denied
                        echo json_encode(array("message" => "name or password is wrong."));
                    }
                } else {
                    http_response_code(401);
                    // tell the user access denied
                    echo json_encode(array("message" => "email format is wrong."));
                }
            } else {

                // set response code
                http_response_code(401);

                // tell the user access denied
                echo json_encode(array("message" => "Wrong message format."));
            }
//            $this->tokenCheck();
        } else {
            http_response_code(401);
            echo json_encode(array(
                "message" => "Wrong Request Method."
            ));
        }
    }

}