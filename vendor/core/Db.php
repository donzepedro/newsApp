<?php
/**
 * Created by PhpStorm.
 * User: SRT
 * Date: 14.01.2019
 * Time: 23:37
 */

namespace vendor\core;


class Db
{
    protected $pdo;
    protected static $instance;
    public static $countSql =0;
    public static $queries = [];

    protected function  __construct()
    {
        $db = require ROOT . '/config/config_db.php';
        $options = [
          \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        ];
        $this->pdo = new \PDO($db['dsn'],$db['user'],$db['pass'],$options);
    }

    public static function instance()
    {
        if(self::$instance === null){
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function execute($sql,$params=[])
    {
        self::$countSql++;
        self::$queries[] = $sql;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function query($sql,$params=[])
    {
        self::$countSql++;
        self::$queries[] = $sql;
        $stmt = $this->pdo->prepare($sql);
        $res = $stmt->execute($params);
        if($res !== false){
            return $stmt->fetchAll();
        }
        return [];
    }
    public function queryinsert($sql,$params=[]){
        $stmt = $this->pdo->prepare($sql);
        for($i=1;$i<=count($params);$i++){
            $stmt->bindParam($i, $params[$i]);
        }
        return $stmt->execute();
    }

    public function querydelete($sql,$params=[]){
        $stmt = $this->pdo->prepare($sql);
        for($i=1;$i<=count($params);$i++){
            $stmt->bindParam($i, $params[$i]);
        }
        return $stmt->execute();
    }
}