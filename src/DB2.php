<?php

namespace c20\api;

use PDO;

class DB2
{
  protected PDO $pdo;

  public function __construct(PDO $pdo)
  {
    $this->pdo = $pdo;
  }

  public function one($class, array $where = [], array $options = []): ?array
  {
    $item = $this->find($class, $where, ['limit' => 1] + $options);
    return $item->current();
  }

  public function find($class, array $where = [], array $options = []): iterable
  {
    foreach(range(0, 5) as $i) {
      yield ['id' => $i, 'name' => crc32($i), 'class' => $class];
    }
  }

  /* public function insert($class, array $data = [])
  {

  }

  public function update($class, array $where, array $data = [])
  {

  }

  public function delete($class, array $where = [])
  {

  } */

  public function hydrate(string $class, array $data): ModelInterface
  {
    $model = new $class;
    $model->import($data);
    return $model;
  }
}

interface ModelInterface
{
  public function import(array $data);
  public function export(): array;
}

abstract class Model implements ModelInterface
{
  protected array $data = [];

  public function import(array $data)
  {
    $this->data = $data;
  }

  public function export(): array
  {
    return $this->data;
  }
}

class DB2User extends Model
{
}

class Mapper
{
  protected DB2 $db;
  protected string $class;

  public function __construct(DB2 $db, string $class)
  {
    $this->db = $db;
    $this->class = $class;
  }

  public function one(array $where, array $options = []): ?ModelInterface
  {
    $data = $this->db->one($this->class, $options);
    return $data ? $this->hydrate($data) : null;
  }

  public function find(array $where, array $options = []): iterable
  {
    foreach($this->db->find($where, $options) as $data) {
      yield $this->hydrate($data);
    }
  }

  public function hydrate($data): ModelInterface
  {
    return $this->db->hydrate($this->class, $data);
  }
}
