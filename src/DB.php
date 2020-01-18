<?php

namespace c20\api;

use RedBeanPHP\R as R;

class DB
{
  public function __construct()
  {
    R::setup();
  }

  public function dispense($type, array $data = [])
  {
    return R::dispense(['_type' => $type] + $data);
  }

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
