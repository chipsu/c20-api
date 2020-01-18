<?php

require_once __DIR__ . '/../../php-core/vendor/autoload.php';

use metrica\core\Bootstrap;
use metrica\core\Router;
use metrica\core\Request;
use metrica\core\Uri;
use metrica\core\Headers;
use metrica\core\StringStream;
use c20\api\DB;
use c20\api\UserModule;

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

describe('UserModule', function() {
  $this->di = di();
  $this->di->get('db')->nuke();

  beforeEach(function() {
    $this->module = new UserModule($this->di->get('router'));
    $this->module->init();
  });

  describe('test route', function() {
    it('create', function() {
      $uri = Uri::parse('/user');
      $headers = Headers::fromArray([
        'HTTP_CONTENT_TYPE' => 'application/json',
      ]);
      $body = new StringStream(json_encode(['username' => 'test']));
      $request = new Request(Request::METHOD_POST, $uri, $headers, null, null, $body);
      $result = $this->di->get('router')->invokeRequest($request);
      assert(is_object($result));
      assert($result->username == 'test');
    });

    it('update', function() {
      $uri = Uri::parse('/user/1');
      $headers = Headers::fromArray([
        'HTTP_CONTENT_TYPE' => 'application/json',
      ]);
      $body = new StringStream(json_encode(['username' => 'test2']));
      $request = new Request(Request::METHOD_PUT, $uri, $headers, null, null, $body);
      $result = $this->di->get('router')->invokeRequest($request);
      assert(is_object($result));
      assert($result->username == 'test2');
    });

    it('list', function() {
      $uri = Uri::parse('/user');
      $request = new Request(Request::METHOD_GET, $uri);
      $result = $this->di->get('router')->invokeRequest($request);
      assert(is_array($result));
      assert(count($result) == 1);
      assert($result[0]['username'] == 'test2');
    });
  });
});
