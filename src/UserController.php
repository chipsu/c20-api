<?php

namespace c20\api;

class UserController extends CrudController
{
  public function init()
  {
    $this->initRoutes('user');

    $pdo = new \PDO($_ENV['C20_DB_DSN']);
    $db = new DB2($pdo);
    $mapper = new Mapper($db, DB2User::class);
    $user = $mapper->one(['id' => 1]);
    var_dump($user);
    die('xxx');
  }
}
