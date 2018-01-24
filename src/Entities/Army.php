<?php
/**
 * Created by PhpStorm.
 * User: igorkos
 * Date: 24/01/18
 * Time: 11:14
 */

namespace App\Entities;

/**
 * Class Army
 * @package App\Entities
 */
class Army {

  public $name;
  public $soldiers;

  /**
   * Army constructor.
   * @param String $name
   * @param array $soldiers
   */
  public function __construct (String $name, array $soldiers) {
    $this->name = $name;
    $this->soldiers = $soldiers;
  }

  /**
   * @return bool
   */
  public function isAlive () {
    return count($this->soldiers) > 0;
  }

  /**
   * @return String
   */
  public function getName () {
    return $this->name;
  }

  /**
   * @return int
   */
  public function getSoldierCount () {
    return count($this->soldiers);
  }

  /**
   * @return mixed
   */
  public function getRandomSoldier () {
    return $this->soldiers[array_rand($this->soldiers)];
  }

  /**
   * @param $soldierKey
   * @param Soldier $soldier
   */
  public function updateSoldier ($soldierKey, Soldier $soldier) {
    $this->soldiers[$soldierKey] = $soldier;
  }

  /**
   * @param Soldier $soldier
   */
  public function removeSoldier (Soldier $soldier) {
    $soldierId = array_search($soldier, $this->soldiers);
    unset($this->soldiers[$soldierId]);
  }
}
