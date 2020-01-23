<?php

namespace c20\api;

use InvalidArgumentException;
use RedBeanPHP\R as R;
use RedBeanPHP\OODBBean;
use Hashids\Hashids;
use metrica\core\EnvInterface;

define('REDBEAN_MODEL_PREFIX', '\\c20\\api\\');

class ValidationError extends \metrica\core\HttpException {
  protected array $errors;

  public function __construct(array $errors)
  {
    parent::__construct(400, print_r($errors, true));
    $this->errors = $errors;
  }

  public function getErrors(): array {
    return $this->errors;
  }

  public function getResponseData(): array
  {
    return $this->getErrors();
  }
}

class DB
{
  protected Hashids $hashids;

  public function __construct(EnvInterface $env, Hashids $hashids)
  {
    $this->hashids = $hashids;
    $dsn = $env->get('C20_DB_DSN', 'sqlite::memory:');
    R::setup($dsn);
  }

  public function one(string $type, array $filter)
  {
    $query = '';
    $params = [];
    $filter = $this->decode($filter);
    foreach($filter as $key => $value) {
      $query .= ' ' . $key . ' = :' . $key . ' ';
      $params[':' . $key] = $value;
    }
    return R::findOne($type, $query, $params);
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
    var_dump(['load', $id]);

    $id = $this->hashids->decode($id)[0]; # todo decode/encode helpers in module
    var_dump(['load', $id]);
    return R::load($type, $id);
  }

  public function store($bean)
  {
    return R::store($bean);
  }

  # TODO: filter stuff not in rules
  # TODO: Move filter away from DB? Maybe all validation?
  # TODO: Separate rule-sets for user and system-data?
  public function validate(OODBBean $bean)
  {
    $errors = $this->errors($bean);
    if($errors) {
      throw $errors;
    }
  }

  public function errors(OODBBean $bean): ?ValidationError
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

  public function encode(array $data): array
  {
    # todo map from model
    $fields = ['id'];
    foreach($fields as $key) {
      if(isset($data[$key])) {
        $data[$key] = $this->hashids->encode($data[$key]);
      }
    }
    return $data;
  }

  public function decode(array $data): array
  {
    # todo map from model
    $fields = ['id'];
    foreach($fields as $key) {
      if(isset($data[$key])) {
        $data[$key] = $this->hashids->decode($data[$key])[0];
      }
    }
    return $data;
  }

  public function export(OODBBean $bean): array
  {
    $data = $bean->export();
    return $this->encode($data);
  }

  public function import(OODBBean $bean, array $data)
  {
    $data = $this->decode($data);
    $bean->import($data);
  }
}
