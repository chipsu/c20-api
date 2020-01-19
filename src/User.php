<?php

namespace c20\api;

class User extends \RedBeanPHP\SimpleModel
{
  public static $filter = [
    'username' => 'trim|sanitize_string',
    'password' => 'trim',
    'email'    => 'trim|sanitize_email',
  ];
  public static $rules = [
    'username'    => 'required|alpha_numeric|max_len,100|min_len,6',
    'password'    => 'required|max_len,100|min_len,6',
    'email'       => 'required|valid_email',
  ];
}
