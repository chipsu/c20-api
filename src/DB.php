<?php

namespace c20\api;

use InvalidArgumentException;
use RedBeanPHP\R as R;

define('REDBEAN_MODEL_PREFIX', '\\c20\\api\\');

class ValidationError extends \metrica\core\Exception {
  protected array $errors;

  public function __construct(array $errors)
  {
    $this->errors = $errors;
  }

  public function getErrors(): array {
    return $this->errors;
  }
}

class DB
{
  public function __construct()
  {
    $dsn = getenv('C20_DB_DSN') ?: 'sqlite::memory:';
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
  # TODO: Move filter away from DB? Maybe all validation?
  public function validate(\RedBeanPHP\OODBBean $bean)
  {
    $errors = $this->errors($bean);
    if($errors) {
      throw $errors;
    }
  }

  public function errors(\RedBeanPHP\OODBBean $bean): ?ValidationError
  {
    $model = $bean->box();
    if(!$model) {
      throw new InvalidArgumentException('$bean does not have a model');
    }
    if(empty($model::$rules)) {
      throw new InvalidArgumentException('$bean does not have any rules');
    }
    $gump = new \GUMP;
    $data = $bean->export();
    if (!empty($model::$filter)) {
      $data = $gump->filter($data, $model::$filter);
    }
    if ($gump->validate($data, $model::$rules) === true) {
      $bean->import($data);
      return null;
    }
    return new ValidationError($gump->get_errors_array());
  }

  public function nuke()
  {
    R::nuke();
  }
}
