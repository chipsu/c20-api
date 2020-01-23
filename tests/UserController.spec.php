<?php

require_once __DIR__ . '/Helpers.php';

use metrica\core\Request;
use metrica\core\Uri;
use metrica\core\Headers;
use metrica\core\StringStream;
use c20\api\UserController;

describe('UserController', function() {
  $this->di = di();
  $this->di->get('db')->nuke();
  $this->user_id = null;

  beforeEach(function() {
    $this->controller = new UserController($this->di->get('router'), $this->di->get('db'));
    $this->controller->init();
  });

  describe('test route', function() {
    it('create', function() {
      $uri = Uri::parse('/user');
      $headers = Headers::fromArray([
        'HTTP_CONTENT_TYPE' => 'application/json',
      ]);
      $body = new StringStream(json_encode(['username' => 'testuser1', 'password' => 'hackers2', 'email' => 'hacker2@example.com']));
      $request = new Request(Request::METHOD_POST, $uri, $headers, null, null, $body);
      $result = $this->di->get('router')->invokeRequest($request);
      assert(is_array($result));
      assert(!empty($result['id']));
      assert($result['username'] == 'testuser1');
      $this->user_id = $result['id'];
    });

    it('update', function() {
      $uri = Uri::parse('/user/' . $this->user_id);
      $headers = Headers::fromArray([
        'HTTP_CONTENT_TYPE' => 'application/json',
      ]);
      $body = new StringStream(json_encode(['username' => 'testuser2']));
      $request = new Request(Request::METHOD_PUT, $uri, $headers, null, null, $body);
      $result = $this->di->get('router')->invokeRequest($request);
      assert(is_array($result));
      assert($result['username'] == 'testuser2');
    });

    it('list', function() {
      $uri = Uri::parse('/user');
      $request = new Request(Request::METHOD_GET, $uri);
      $result = $this->di->get('router')->invokeRequest($request);
      assert(is_array($result));
      assert(count($result) == 1);
      assert($result[0]['username'] == 'testuser2');
    });
  });
});
