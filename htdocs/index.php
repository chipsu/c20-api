<?php

namespace c20\api;

# TODO: Move to .env
define('C20_DB_DSN', 'sqlite:/tmp/c20.db');
define('C20_JWT_SECRET', 'secret');

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../../php-core/vendor/autoload.php';

use metrica\core\Bootstrap;
use Ahc\Jwt\JWT;

$modules = [
  'authModule' => AuthModule::class,
  'userModule' => UserModule::class,
  'contentModule' => ContentModule::class,
];

$di = Bootstrap::di([
  'db' => DB::class,
  'jwt' => function() {
    return new JWT(C20_JWT_SECRET);
  },
] + $modules);

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

foreach(array_keys($modules) as $module) {
  $di->get($module)->init();
}

$di->get('app')->run();
