<?php

namespace c20\api;

class UserModule extends CrudModule
{
  public function init()
  {
    $this->initRoutes('user');
  }
}
