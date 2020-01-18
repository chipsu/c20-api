<?php

namespace c20\api;

use RedBeanPHP\R as R;

class DB
{
  public function __construct()
  {
    $dsn = 'sqlite:/tmp/c20.db';
    #$dsn = 'sqlite:memory:';
    R::setup($dsn);
  }

  public function find($type)
  {
    return R::find($type);
  }

  public function dispense($type, array $data = [])
  {
    return R::dispense(['_type' => $type] + $data);
  }

  public function load($type, $id)
  {
    return R::load($type, $id);
  }

  public function store($bean)
  {
    return R::store($bean);
  }

  # TODO: filter stuff not in rules
  public function validate($bean)
  {
    $gump = new \GUMP;
    $model = $bean->box();
    $data = $bean->export();
    if (!empty($model->filter)) {
      $data = $gump->filter($data, $model->filter);
    }
    if (!empty($model->rules)) {
      if (!$gump->validate($data, $model->rules)) {
        print_r($gump->get_errors_array());
        throw new Exception(('invalid data'));
      }
    }
    $bean->import($data);
  }
}
