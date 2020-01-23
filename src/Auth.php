<?php

namespace c20\api;

use Ahc\Jwt\JWT;
use Ahc\Jwt\JWTException;
use metrica\core\RequestInterface;
use metrica\core\HttpException;

# Resource,Method,Allow
# user,*,false
# user,post,true

# new Events([RouterInterface::class, 'beforeInvoke', $checkCredentials])
# GET user/1 auth->is(['admin', 'uid' => 1])

# GET order/xxx
# auth->credentials()
# auth->is(['admin', 'uid' => 1])

class Auth
{
  protected JWT $jwt;

  public function __construct(JWT $jwt)
  {
    $this->jwt = $jwt;
  }

  public function credentials(RequestInterface $request)
  {
    $auth = explode(' ', $request->getHeader('Authorization', ''), 2);
    if(count($auth) != 2 || $auth[0] != 'Bearer') {
      throw new HttpException(400, 'Invalid Bearer');
    }
    if($request->getParam('testExpiry')) {
      $this->jwt->setTestTimestamp(time() + 10000);
    }
    try {
      return $this->jwt->decode($auth[1]);
    } catch(JWTException $ex) {
      throw new HttpException(400, $ex->getMessage());
    }
  }

  public function is($role) {
    return true;
  }
}
