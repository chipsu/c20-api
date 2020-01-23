<?php

namespace c20\api;

use RedBeanPHP\SimpleModel;

class Order extends SimpleModel
{
  public static $filter = [
    'shipping_company' => 'trim',
  ];

  public static $rules = [
    'shipping_company' => 'required',
  ];

  public static $rulesX = [
    'shipping_company' => 'required',
    'shipping_name' => 'required',
    'shipping_address' => 'required',
    'shipping_address_2' => 'required',
    'shipping_address_city' => 'required',
    'shipping_address_county' => 'required',
    'shipping_address_country' => 'required',
    'shipping_address_zipcode' => 'required',
    'invoice_company' => 'required',
    'invoice_name' => 'required',
    'invoice_address' => 'required',
    'invoice_address_2' => 'required',
    'invoice_address_city' => 'required',
    'invoice_address_county' => 'required',
    'invoice_address_country' => 'required',
    'invoice_address_zipcode' => 'required',
    'customer_email' => 'required',
    'customer_phone' => 'required',
    'customer_phone_2' => 'required',
    'customer_comment' => 'required',
    'discount' => 'required',
    'discount_code' => 'required',
    'total' => 'required',
    'total_vat' => 'required',
    'shipping' => 'required',
    'shipping_vat' => 'required',
    'shipping_method' => 'required',
    'shipping_tracking_code' => 'required',
    'payment_method' => 'required',
    'payment_currency' => 'required',
    'received_at' => 'required',
    'dispatched_at' => 'required',
    'status' => 'required',
    'comment' => 'required',
  ];
}
