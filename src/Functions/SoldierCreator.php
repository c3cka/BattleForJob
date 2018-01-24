<?php
/**
 * Created by PhpStorm.
 * User: igorkos
 * Date: 24/01/18
 * Time: 10:42
 *
 *
 * Function to create soldiers
 * It creates soldier by randomly selecting its class
 * Every soldier class has its own attribute modifiers
 */

namespace App\Functions;

use App\Entities\Soldier;
use App\Traits\Skill;
use App\Traits\SpecialSkill;

/**
 * Class SoldierCreator
 * @package App\Functions
 */
class SoldierCreator {

  const MAIN_HEALTH = 100;
  const MAIN_DAMAGE = 25;
  const MAIN_ARMOUR = 10;
  const MAIN_MISS = 2;
  const MAIN_LUCK = 1 ;

  private $soldierClasses;

  use Skill, SpecialSkill;
  /**
   * SoldierCreator constructor.
   */
  public function __construct () {

    $this->soldierClasses = [
      'Assault' => [
        'healthModifier' => 1.8,
        'damageModifier' => 0.2,
        'armourModifier' => 1.2,
        'missModifier' => 1.0,
        'luckModifier' => rand(10, 30),
      ],
      'Heavy' => [
        'healthModifier' => 0.8,
        'damageModifier' => 1.7,
        'armourModifier' => 1.0,
        'missModifier' => 1.0,
        'luckModifier' => rand(10, 30),
      ],
      'Support' => [
        'healthModifier' => 0.4,
        'damageModifier' => 2.0,
        'armourModifier' => 0.4,
        'missModifier' => 1.0,
        'luckModifier' => rand(10, 30),
      ],
      'Sniper' => [
        'healthModifier' => 1.0,
        'damageModifier' => 5.0,
        'armourModifier' => 1.0,
        'missModifier' => 7.0,
        'luckModifier' => rand(10, 30),
      ],
    ];
  }

  /**
   * @param $armyName
   * @param $numOfSoldiers
   * @return array of soldiers
   */
  public function generateSoldiers ($armyName, $numOfSoldiers) {
    $soldiers = [];
    for ($i = 1; $i < $numOfSoldiers; ++$i) {
      $className = array_rand($this->soldierClasses);
      $stats = $this->generateStats($className);
      $soldiers[] = new Soldier($armyName, $className, $stats);
    }
    return $soldiers;
  }

  /**
   * @param $className
   * @return array of modified stats
   */
  protected function generateStats ($className) {
    $modifiers = $this->soldierClasses[$className];
    return [
      'health' => self::MAIN_HEALTH * $modifiers['healthModifier'],
      'damage' => self::MAIN_DAMAGE * $modifiers['damageModifier'],
      'armour' => self::MAIN_ARMOUR * $modifiers['armourModifier'],
      'missPercent' => self::MAIN_MISS * $modifiers['missModifier'],
      'luck' => self::MAIN_LUCK * $modifiers['luckModifier'],
    ];
  }
}
