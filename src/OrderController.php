<?php

namespace c20\api;

use metrica\core\RequestInterface;

class OrderRow extends \RedBeanPHP\SimpleModel
{

}

class OrderController extends CrudController
{
  public function init()
  {
    $this->initRoutes('order', 'secret', ['orderrow']);
    $this->router->post('/order/<secret>/row', [$this, 'createRow']);
    $this->router->put('/order/<secret>/row', [$this, 'updateRow']);
    $this->router->delete('/order/<secret>/row', [$this, 'deleteRow']);
  }

  public function dispense(array $data)
  {
    $secret = bin2hex(openssl_random_pseudo_bytes(16));
    return parent::dispense(['secret' => $secret] + $data);
  }

  public function createRow(RequestInterface $request)
  {
    $bean = $this->db->one($this->type, [$this->pk => $request->getParam($this->pk)], $this->with);
    if(!$bean) {
      return 404;
    }
    $row = $this->db->dispense('orderrow', $request->getBodyParams());
    $bean->ownRows[] = $row;
    $this->db->store($bean);
    $rows = $this->db->find('orderrow');
    var_dump($rows);
    return $this->db->export($bean);
  }

  public function updateRow()
  {

  }

  public function deleteRow()
  {

  }
}
