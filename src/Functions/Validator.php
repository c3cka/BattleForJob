<?php
/**
 * Created by PhpStorm.
 * User: igorkos
 * Date: 24/01/18
 * Time: 10:35
 */

/** Validates if forwarded parameters are numeric */

namespace App\Functions;

class Validator {
  public function validate(array $params) {
    foreach ($params as $param) {
      if (!$param || !is_numeric($param)) {
        echo "Army unit count must be numeric value!";
        return false;
      }
    }
    return true;
  }
}
