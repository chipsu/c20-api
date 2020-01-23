<?php

namespace c20\api;

class UserController extends CrudController
{
  public function init()
  {
    $this->initRoutes('user');
  }
}
