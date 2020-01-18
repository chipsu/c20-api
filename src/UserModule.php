<?php

namespace c20\api;

use metrica\core\RouterInterface;

class UserModule extends CrudModule
{
  public function init()
  {
    $this->router->get('/user/<id>', function (string $id) {
      return 'USER:' . $id;
      #$this->crud->model(User::class);
    });
  }
}
