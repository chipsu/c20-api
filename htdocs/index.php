<?php

namespace c20\api;

define('C20_DB_DSN', 'sqlite:/tmp/c20.db');

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../../php-core/vendor/autoload.php';

use metrica\core\Bootstrap;

$di = Bootstrap::di([
  'db' => DB::class,
  'userModule' => UserModule::class,
  'contentModule' => ContentModule::class,
]);

$di->get('router')->get('/', function (DB $db) {
  echo '<pre>';
  $data = ['username' => ' moo moo '];
  print_r($data);
  $user = $db->dispense('user', $data);
  print_r($db->validate($user));
  printf("username='%s'", $user->username);
  return [
    'version' => '1',
  ];
});

$di->get('userModule')->init();

$di->get('app')->run();
