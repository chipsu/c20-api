<?php

# RB models don't work well with different namespaces :(
namespace c20\api;

require_once __DIR__ . '/Helpers.php';

use c20\api\User;
use c20\api\ValidationError;

class Dummy extends \RedBeanPHP\SimpleModel
{
  public static $filter = [
    'name' => 'trim',
  ];
  public static $rules = [
    'name' => 'required|alpha_numeric',
  ];
}

describe('Model validation', function() {
  $this->di = di();
  $this->db = $this->di->get('db');
  $this->db->nuke();

  beforeEach(function() {
  });

  describe('test', function() {
    it('filter', function() {
      $bean = $this->db->dispense('dummy', ['name' => ' trim ']);
      $this->db->validate($bean);
      assert($bean->name === 'trim');
    });

    it('validation succeeeds', function() {
      $bean = $this->db->dispense('dummy', ['name' => 'alphanumeric']);
      $errors = $this->db->errors($bean);
      assert($errors === null);
    });

    it('validation', function() {
      $bean = $this->db->dispense('dummy', ['name' => 'not!alpha!numeric']);
      $errors = $this->db->errors($bean);
      assert($errors instanceof ValidationError);
    });
  });
});
