<?php

class Poop
{
  public $role;
  public $keys = [];
}

class Poopypie
{

}

// DB + ROUTES?

$restRule = new Poop([
  'role' => 'guest',
  'action' => 'grant',
  'attributes' => [
    'action' => 'read',
    'table' => 'blog_post',
    'field' => [  // compiles to /id|title/
      'id',
      'title',
    ],
  ],
]);

// foreach($fields as $k => $v)
//   getRule(['action' => 'read', 'resource' => $table, 'field' => $k]) or 403
//

// member: GET /blog
$restRule = new Poop([
  'type' => 'resource',
  'action' => 'grant',
  'conditions' => [
    'is_owner',
  ],
  'attributes' => [
    'role' => 'member',
    'action' => 'update'
    'table' => 'blog_post',
    'field' => [
      'title',
    ],
  ],
]);

$restRule = new Poop([
  'type' => 'route',
  'action' => 'grant',
  'attributes' => [
    'role' => 'member',
    'method' => '/PUT/',
    'route' => '/stuff',
    'attributes' => [
      'title',
    ],
  ],
]);


final class OrderStatus {
  public const Cancelled = 0;
  public const Active = 1;
  public const Processing = 1;
  public const PaymentDue = 1;
  public const InTransit = 1;
  public const PickupAvailable = 1;
  public const Delivered = 1;
  public const Returned = 1;
  public const Problem = 1;
}

class OrderRow {

  public static $accessRules = [
    [ # create
      'role' => ['guest', 'user'],
      'action' => 'create',
      'criteria' => [
        'order.secret' => 'secret',
        'order.status' => OrderStatus::Active,
      ],
      'attributes' => ['product', 'amount', 'message'],
    ],
    [ # update
      'role' => ['guest', 'user'],
      'action' => 'update',
      'criteria' => [
        'order.secret' => 'secret',
        'order.status' => OrderStatus::Active,
      ],
      'attributes' => ['amount', 'message', 'sku/product?'],
    ],
    [ # delete
      'role' => ['guest', 'user'],
      'action' => 'delete',
      'criteria' => [
        'order.secret' => 'secret',
        'order.status' => OrderStatus::Active,
      ],
    ],
  ];
}

class Order {
  public static $accessRules = [
    [ # direct link
      'role' => ['guest', 'user'],
      'action' => 'read',
      'criteria' => [
        'secret' => 'secret',
      ],
      'attributes' => ['*'],
    ],
    [ # create
      'role' => ['guest', 'user'],
      'action' => 'create',
      'criteria' => [
      ],
      'attributes' => ['*'],
    ],
    [ # update
      'role' => ['guest', 'user'],
      'action' => 'update',
      'criteria' => [
        'secret' => 'secret',
        'status' => OrderStatus::Active,
      ],
      'attributes' => ['message'],
    ],
    [ # user listing
      'role' => 'user',
      'action' => 'read',
      'criteria' => [
        'user_id' => 'uid',
      ],
      'attributes' => ['id', 'secret', 'message', 'rows', 'currency', 'total', 'vat', 'invoice_address', 'shipping_price', 'shipping_vat', 'shipping_method', 'shipping_address'],
    ],
    [ # all for admin (not needed, default)
      'role' => 'admin',
      'action' => '*',
      'attributes' => '*',
    ],
  ];
}



