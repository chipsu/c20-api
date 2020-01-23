<?php

namespace c20\api;

use metrica\core\RequestInterface;
use metrica\core\RouterInterface;

abstract class CrudController
{
  protected RouterInterface $router;
  protected DB $db;
  protected ?string $type;
  protected ?string $pk;

  public function __construct(RouterInterface $router, DB $db)
  {
    $this->router = $router;
    $this->db = $db;
  }

  public function initRoutes(string $type, string $pk = 'id')
  {
    $this->type = $type;
    $this->pk = $pk;
    $this->router->get('/' . $type . '/<' . $pk . '>', [$this, 'one']);
    $this->router->get('/' . $type, [$this, 'read']);
    $this->router->put('/' . $type . '/<' . $pk . '>', [$this, 'update']);
    $this->router->post('/' . $type, [$this, 'create']);
  }

  public function one(RequestInterface $request)
  {
    $bean = $this->db->one($this->type, [$this->pk => $request->getParam($this->pk)]);
    if(!$bean) {
      return 404;
    }
    return $this->db->export($bean);
  }

  public function read(RequestInterface $request, DB $db)
  {
    $beans = $db->find($this->type);
    return array_map(function($item) use($db) {
      return $db->export($item);
    }, array_values($beans));
  }

  public function update(DB $db, RequestInterface $request)
  {
    $bean = $this->db->one($this->type, [$this->pk => $request->getParam($this->pk)]);
    if(!$bean) {
      return 404;
    }
    $db->import($bean, $request->getBodyParams());
    $db->validate($bean);
    $db->store($bean);
    return $db->export($bean);
  }

  public function create(DB $db, RequestInterface $request)
  {
    $bean = $this->dispense($request->getBodyParams());
    $db->validate($bean);
    $db->store($bean);
    return $db->export($bean);
  }

  public function dispense(array $data)
  {
    return $this->db->dispense($this->type, $data);
  }
}

