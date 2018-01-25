<?php
/**
 * Created by PhpStorm.
 * User: igorkos
 * Date: 24/01/18
 * Time: 11:22
 */

namespace App\Game;

use App\Entities\Army;

/**
 * Class Start
 * @package App\Game
 */
class Start {

  protected $turn;
  protected $winner;
  protected $armies;
  protected $attackSoldier;
  protected $defendSoldier;

  /**
   * Start constructor.
   * @param Army $army1
   * @param Army $army2
   */
  public function __construct (Army $army1, Army $army2) {
    $this->turn = 0;
    $this->armies[$army1->name] = $army1;
    $this->armies[$army2->name] = $army2;
  }

  /**
   * @return array
   */
  public function start () {
    while ($this->armies['C3cK@s']->isAlive() && $this->armies['Degordians']->isAlive()) {
      $this->doTurn();
    }

    return [
      'winner' => $this->getWinner(),
      'totalTurns' => $this->turn
    ];
  }

  /**
   * Simulates battle by turns
   */
  protected function doTurn () {
    // Randomly chooses attackSoldier and defendSoldier from different armies
    $this->selectAttackSoldierAndDefendSoldier();
    echo '<h3># Turn ' . ($this->turn + 1) . ' - ' .
      $this->attackSoldier->name . '[' . $this->attackSoldier->armyName . '] ' . '{' . $this->attackSoldier->class .
      '}' . ' attacks ' . $this->defendSoldier->name . '[' . $this->defendSoldier->armyName . ']' . '</h3><br>';

    if (!$this->steppedOnLandmine()) {
      // Includes soldier performance issue and modifies its stats
      $this->includeSoldierPerformanceIssue();
      // attackSoldier shoots the enemy
      $this->shoot();
    }
    // Removes soldiers that died on the field form the army
    $this->checkForDeadSoldier();

    // Count turns
    ++$this->turn;
    echo '<hr>';
  }

  /**
   * Gets array keys from armies array into temporary array
   * and shuffles it to randomly select attackSoldier and defendSoldier
   */
  protected function selectAttackSoldierAndDefendSoldier () {
    $armiesKeys = array_keys($this->armies);
    shuffle($armiesKeys);

    $attackingArmyKey = array_pop($armiesKeys);
    $defendingArmyKey = array_pop($armiesKeys);

    $this->attackSoldier = $this->armies[$attackingArmyKey]->getRandomSoldier();
    $this->defendSoldier = $this->armies[$defendingArmyKey]->getRandomSoldier();
  }

  /**
   * Soldiers performance issues to make the battle more interesting
   */
  protected function includeSoldierPerformanceIssue () {
    /*
     * If defendSoldier is superior to attackSoldier, attackSoldier get afraid and his
     * chance to miss the defendSoldier is growing
     */
    if ($this->attackSoldier->getHealth() < ($this->defendSoldier->getHealth() / 2)) {
      ++$this->attackSoldier->missPercent;
      echo '<strong><font color="#8b008b">' . $this->attackSoldier->name . '[' . $this->attackSoldier->armyName . ']'
        . ' got afraid! </font></strong><br>';
    }
  }

  protected function steppedOnLandmine () {
    $rand = mt_rand(1, 100);
    if ($rand <= 30) {
      $this->armies[$this->attackSoldier->armyName]->removeSoldier($this->attackSoldier);
      echo '<strong><font color="#d2691e">' . $this->attackSoldier->name . '[' .
        $this->attackSoldier->armyName . '] has stepped on landmine an died! </font></strong>';
      return true;
    }
    return false;
  }

  /**
   * Simulate attack by dealing damage to defendSoldier that is calculated this way:
   * 1) If defendSoldier is lucky, he dodges tha attack, no one is hurt, going to next turn
   * 2) If defendSoldier has got specialSkill and is lucky gets one of defending specialSkills like MagicShield or
   * FirstAid
   * 3) If attackerSoldier has got specialSkill and is lucky gets specialSkill RapidStrike and attacks twice
   */
  protected function shoot () {

    if ($this->defendSoldier->isLucky()) {
      echo '<strong><font color="blue">' . $this->defendSoldier->armyName . ' dodge the hit from ' .
        $this->attackSoldier->armyName . '</font></strong><br>';
    } else {

      if ($this->defendSoldier->hasSpecialSkills()) {
        $defendSkill = $this->defendSoldier->defenseLuck();
        if ($defendSkill != false) {
          echo '<strong><font color="green">' . $this->defendSoldier->name . ' has got ' . $defendSkill['name'] .
            '</font></strong><br>';
          switch ($defendSkill['key']) {
            case "magic_shield":
              $this->attackSoldier->damage = $this->attackSoldier->damage / 2;
              break;
            case "first_aid":
              echo 'Sorry, our soldiers don`t have bandages.' . PHP_EOL;
              break;
          }
        }
      }

      if ($this->attackSoldier->hasSpecialSkills()) {
        $attackSkill = $this->attackSoldier->attackLuck();
        if ($attackSkill != false) {
          echo '<strong><font color="green">' . $this->attackSoldier->name . ' has got ' . $attackSkill['name'] .
            '</font> </strong><br>';
          switch ($attackSkill['key']) {
            case "rapid_strike":
              $this->logAttack();
              $this->meleeAttack();
              break;
          }
        }
      }

      $this->logAttack();
      $this->meleeAttack();
    }
  }

  /**
   * Second attack if specialSkill RapidStrike is activated
   */
  public function meleeAttack () {
    $newHp = $this->defendSoldier->getHealth() - $this->attackSoldier->getDamage();
    $this->defendSoldier->setHealth($newHp);
  }


  /**
   * Outputthe result of each turn
   */
  public function logAttack () {
    echo $this->attackSoldier->name .'[' . $this->attackSoldier->getHealth() .
      ' hp] attacked ' .
      $this->defendSoldier->armyName . '[' .
      $this->defendSoldier->getHealth() . ' hp] with ' . $this->attackSoldier->damage . ' damage!' . '<br>' . PHP_EOL;
  }


  /**
   * Removes soldiers from armies array if his health <= 0, which means he's dead
   */
  protected function checkForDeadSoldier () {
    if ($this->attackSoldier->getHealth() <= 0) {
      $this->armies[$this->attackSoldier->armyName]->removeSoldier($this->attackSoldier);
      echo '<strong>' . $this->attackSoldier->name . '[' .
        $this->attackSoldier->armyName . '] has died! </strong>';
    }

    if ($this->defendSoldier->getHealth() <= 0) {
      $this->armies[$this->defendSoldier->armyName]->removeSoldier($this->defendSoldier);
      echo '<strong><font color="red">' . $this->defendSoldier->name . '[' . $this->defendSoldier->armyName .
        '] has died! </font></strong>';
    }
  }

  /**
   * Returns only living army as winner
   */
  protected function getWinner () {
    foreach ($this->armies as $army) {
      if ($army->isAlive()) {
        return $army;
      }
    }
  }
}
