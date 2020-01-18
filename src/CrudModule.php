<?php

namespace c20\api;

use metrica\core\RouterInterface;

abstract class CrudModule
{
  protected RouterInterface $router;

  public function __construct(RouterInterface $router)
  {
    $this->router = $router;
  }

  /*
  public function model($class)
  {
    $this->router->get('<id>', function (int $id, $jwt) {
      $user = User::pk($id);
      return $user;
    });
  } */
}
