<?php

namespace c20\api;

use metrica\core\RouterInterface;

class OrderController extends CrudController
{
  public function init()
  {
    $this->initRoutes('order', 'secret');
    $this->router->post('/order/<secret>/row', [$this, 'createRow']);
    $this->router->put('/order/<secret>/row', [$this, 'updateRow']);
    $this->router->delete('/order/<secret>/row', [$this, 'deleteRow']);
  }

  public function dispense(array $data)
  {
    $secret = bin2hex(openssl_random_pseudo_bytes(16));
    return parent::dispense(['secret' => $secret] + $data);
  }

  public function createRow(RequestInterface $request, DB $db, string $id)
  {
  }

  public function updateRow()
  {

  }

  public function deleteRow()
  {

  }
}
