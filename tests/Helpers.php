<?php

require_once __DIR__ . '/../../php-core/vendor/autoload.php';

use c20\api\Bootstrap;

function di() {
  static $di = null;
  if($di === null) {
    $_ENV['C20_DB_DSN'] = 'sqlite::memory:';
    define('C20_MODULES', '/.*/');
    define('C20_JWT_SECRET', 'secret');
    define('C20_HASHIDS_KEY', 'secret');
    define('C20_HASHIDS_LENGTH', 5);
    $di = Bootstrap::di();
  }
  return $di;
}
