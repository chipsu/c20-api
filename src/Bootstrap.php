<?php

namespace c20\api;

use metrica\core\Bootstrap as CoreBootstrap;
use metrica\core\DependsInterface;
use Ahc\Jwt\JWT;
use Hashids\Hashids;

class Bootstrap extends CoreBootstrap
{
  public static function di(array $extraDependencies = []): DependsInterface
  {
    $modules = [
      'core' => [
        'authController' => AuthController::class,
        'userController' => UserController::class,
      ],
      'content' => [
        'contentController' => ContentController::class,
      ],
      'ecommerce' => [
        'orderController' => OrderController::class,
      ],
    ];

    $depends = call_user_func_array('array_merge', array_map(fn($module) => $module, $modules));

    $di = parent::di([
      'db' => DB::class,
      'jwt' => function() {
        return new JWT(C20_JWT_SECRET);
      },
      'hashids' => function() {
        return new Hashids(C20_HASHIDS_KEY, C20_HASHIDS_LENGTH);
      },
      'auth' => Auth::class,
    ] + $depends + $extraDependencies);

    foreach($modules as $module => $depends) {
      if(preg_match(C20_MODULES, $module)) {
        foreach(array_keys($depends) as $depend) {
          $di->get($depend)->init();
        }
      }
    }

    return $di;
  }
}
