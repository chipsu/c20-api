<?php

namespace c20\api;

use metrica\core\RequestInterface;
use metrica\core\RouterInterface;

abstract class CrudModule
{
  protected RouterInterface $router;

  public function __construct(RouterInterface $router)
  {
    $this->router = $router;
  }

  public function initRoutes($type)
  {
    $this->router->get('/' . $type, function (DB $db) use($type) {
      return array_map(function($item) {
        return $item->export();
      }, array_values($db->find($type)));
    });

    $this->router->get('/' . $type . '/<id>', function (DB $db, string $id) use($type) {
      $user = $db->load($type, $id);
      if($user->id == 0) {
        return 404;
      }
      return $user->export();
    });

    $this->router->put('/' . $type . '/<id>', function (DB $db, string $id, RequestInterface $request) use($type) {
      $user = $db->load($type, $id);
      if($user->id == 0) {
        return 404;
      }
      $user->import($request->getBodyParams());
      $db->validate($user);
      $db->store($user);
      return $user;
    });

    $this->router->post('/' . $type, function (DB $db, RequestInterface $request) use($type) {
      $user = $db->dispense($type, $request->getBodyParams());
      $db->validate($user);
      $db->store($user);
      return $user;
    });
  }
}
