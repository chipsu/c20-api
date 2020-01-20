<?php

namespace c20\api;

use metrica\core\RouterInterface;
use metrica\core\RequestInterface;
use Ahc\Jwt\JWT;
use Ahc\Jwt\JWTException;
use metrica\core\HttpException;

class AuthModule
{
  protected RouterInterface $router;

  public function __construct(RouterInterface $router)
  {
    $this->router = $router;
  }

  public function init()
  {
    $this->router->get('/auth', function (RequestInterface $request, JWT $jwt) {
      $auth = explode(' ', $request->getHeader('Authorization', ''), 2);
      if(count($auth) != 2 || $auth[0] != 'Bearer') {
        throw new HttpException(400, 'Invalid Bearer');
      }
      if($request->getParam('testExpiry')) {
        $jwt->setTestTimestamp(time() + 10000);
      }
      try {
        $payload = $jwt->decode($auth[1]);
        return $payload;
      } catch(JWTException $ex) {
        throw new HttpException(400, $ex->getMessage());
      }
    });

    $this->router->post('/auth', function (JWT $jwt) {
      $token = $jwt->encode([
        'uid' => 1,
        'aud' => 'http://site.com',
        'scopes' => ['user'],
        'iss' => 'http://api.mysite.com',
      ]);
      return [
        'token' => $token,
      ];
    });
  }
}
