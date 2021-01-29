<?php
/**
 * Created by PhpStorm.
 * User: SRT
 * Date: 14.01.2019
 * Time: 2:25
 */

namespace vendor\core\base;
use Firebase\JWT\JWT;

abstract class Controller
{
    public $route = [];
    public $view;
    public $layout;
    public $vars = [];

    public function __construct($route)
    {
        $this->route = $route;
        $this->view =$route['action'];
    }

    public function getView()
    {
        $vObj = new View($this->route, $this->layout, $this->view );
        $vObj->render($this->vars);
    }

     public function set($vars)
     {
            $this->vars =$vars;
     }

}