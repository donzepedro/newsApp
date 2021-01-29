<?php
/**
 * Created by PhpStorm.
 * User: SRT
 * Date: 14.01.2019
 * Time: 23:37
 */

namespace vendor\core\base;
use vendor\core\Db;
//require '../../../public/rb.php';


abstract class Model
{
  protected $pdo;
  protected $table;



  public function __construct()
  {
      $this->pdo = Db::instance();
  }

  public function query($sql)
  {
      return $this->pdo->execute($sql);
  }

  public function findAll()
  {
      $sql = "SELECT * FROM {$this->table}";
      return $this->pdo->query($sql);
  }

  public function findBySql($sql,$params = []){
      return $this->pdo->query($sql,$params);
  }
  public function setBySql($sql,$params = []){
      return $this->pdo->execute($sql,$params);
  }
  public function insertBySql($sql,$params=[]){
      return $this->pdo->queryinsert($sql,$params);
  }
  public function deleteBySql($sql,$params=[]){
      return $this->pdo->querydelete($sql,$params);
  }
}