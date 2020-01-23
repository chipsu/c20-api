<?php

namespace c20\api;

use metrica\core\RouterInterface;
use metrica\core\RequestInterface;
use Ahc\Jwt\JWT;

class AuthController
{
  protected RouterInterface $router;

  public function __construct(RouterInterface $router)
  {
    $this->router = $router;
  }

  public function init()
  {
    $this->router->get('/auth', function (RequestInterface $request, Auth $auth) {
      $credentials = $auth->credentials($request);
      return ['exp' => $credentials['exp']];
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
