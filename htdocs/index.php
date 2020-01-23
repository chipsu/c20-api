<?php

namespace c20\api;

# TODO: Move to .env
$_ENV['C20_DB_DSN'] = 'sqlite:/tmp/c20.db';
define('C20_MODULES', '/.*/');
define('C20_JWT_SECRET', 'secret');
define('C20_HASHIDS_KEY', 'secret');
define('C20_HASHIDS_LENGTH', 5);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../../php-core/vendor/autoload.php';

use metrica\core\Error;

Error::init();

$di = Bootstrap::di();

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

$di->get('app')->run();
