<?php

namespace c20\api;

use metrica\core\RequestInterface;
use metrica\core\RouterInterface;

class UserModule extends CrudModule
{
  public function init()
  {
    $this->router->get('/user', function (DB $db) {
      return array_map(function($item) {
        return $item->export();
      }, array_values($db->find('user')));
    });

    $this->router->get('/user/<id>', function (DB $db, string $id) {
      $user = $db->load('user', $id);
      if($user->id == 0) {
        return 404;
      }
      return $user->export();
    });

    $this->router->put('/user/<id>', function (DB $db, string $id, RequestInterface $request) {
      $user = $db->load('user', $id);
      if($user->id == 0) {
        return 404;
      }
      $user->import($request->getBodyParams());
      $db->validate($user);
      $db->store($user);
      return $user;
    });

    $this->router->post('/user', function (DB $db, RequestInterface $request) {
      $user = $db->dispense('user', $request->getBodyParams());
      $db->validate($user);
      $db->store($user);
      return $user;
    });
  }
}
