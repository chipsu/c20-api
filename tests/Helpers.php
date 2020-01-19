<?php

require_once __DIR__ . '/../../php-core/vendor/autoload.php';

use metrica\core\Bootstrap;
use c20\api\DB;

function di() {
  static $di = null;
  if($di === null) {
    putenv('C20_DB_DSN=sqlite::memory:');
    $di = Bootstrap::di([
      'db' => DB::class,
    ]);
  }
  return $di;
}
