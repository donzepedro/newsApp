<?php
/**
 * Created by PhpStorm.
 * User: SRT
 * Date: 14.01.2019
 * Time: 12:46
 */

namespace vendor\core\base;


class View
{
    public $route = [];
    public $view;
    public $layout;

    public function __construct($route,$layout = '',$view = '')
    {
//        var_dump($layout);
//        var_dump($view);
            $this->route=$route;
            $this->view=$view;
            $this->layout = $layout ?: LAYOUT;

    }

    public function render($vars)
    {
        if(is_array($vars))extract($vars);
        $file_view = APP . "/views/{$this->route['controller']}/{$this->view}.php";
        ob_start();
        if(is_file($file_view)){
            require $file_view;
        }else{
            echo "<p>View does not found <b>{$file_view}</b></p>";
        }
        $content = ob_get_clean();
        $file_layout = APP . "/views/layouts/{$this->layout}.php";
        if(is_file($file_layout)){
            require $file_layout;
        }else{
            echo "<p>Layout does not found <b>{$file_layout}</b></p>";
        }
    }

}