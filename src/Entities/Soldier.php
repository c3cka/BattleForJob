<?php
/**
 * Created by PhpStorm.
 * User: igorkos
 * Date: 24/01/18
 * Time: 10:48
 */

namespace App\Entities;

use App\Traits\Skill;
use App\Traits\SpecialSkill;

/**
 * Class Soldier
 * @package App\Entities
 */
class Soldier {

  public $armyName;
  public $name;
  public $class;
  public $health;
  public $armour;
  public $damage;
  public $missPercent;
  public $luck;

  use Skill, SpecialSkill;
  /**
   * Soldier constructor.
   * @param $armyName
   * @param $class
   * @param $stats
   */
  public function __construct ($armyName, $class, $stats) {
    $this->armyName = $armyName;
    $this->class = $class;
    $this->health = $stats['health'];
    $this->armour = $stats['armour'];
    $this->damage = $stats['damage'];
    $this->missPercent = $stats['missPercent'];
    $this->luck = $stats['luck'];
    $this->setName();
  }

  public function setName () {
    $names = [
      'Wolverine',
      'Dr. Strange',
      'Hulk',
      'Iron Man',
      'Thor',
      'Loki',
      'Ant Man',
      'Spider Man',
      'Captain America',
      'Quicksilver',
      'Ancient One',
      'Wiccan',
      'Groot',
      'Gwenpool',
      'Mordo',
      'Black Bolt',
      'Ronan',
      'Ultron',
      'Odin',
      'Domammu',
      'Beast',
      'Cyclops',
      'Storm',
      'Rogue',
      'Carnage',
      'Goliath',
      'Hulkbuster',
      'Iron Fist',
      'Moon Knight',
      'Punisher',
      'Sif',
      'Kaecilius',
      'Malekith',
      'Mantis',
      'Sister Grimm',
      'Songbird',
      'Wasp',
      'Yellowjacket',
      'Black Widow',
      'Daredevil',
      'Elsa Bloodstone',
      'Green Goblin',
      'Silk',
      'Angela',
      'Blue Marvel',
      'Captain Marvel',
      'Clea',
      'Ghost Rider',
      'Hela',
      'Hellstorm',
      'Nova',
      'Vision',
      'Venom',
      'Daisy Johnson',
      'Hawkeye',
      'Kingpin',
      'Black Panther',
      'Blade',
      'Crossbones',
      'Deathlok',
      'Luke Cage',
      'Titania',
      'Crystal',
      'Inferno',
      'Lash',
      'Maximus',
      'Mysterio',
      'Rocket Raccoon',
      'War Machine',
      'Falcon',
      'Karnak',
      'Mockingbird',
      'Red Skull',
      'Winter Soldier',
      'Yondu',
      'Heimdall',
      'Hyperion',
      'Gorgon',
      'Dr. Octopus',
      'Drax',
      'Nebula',
      'Shang-Chi',
      'Phil Coulson',
      'Star-Lord',
      'Black Cat',
      'Elektra',
      'Spider-Gwen',
      'Skurge',
      'Bullseye',
      'Enchantress',
      'Lincoln Campbell',
      'Agent 13',
      'Gamora',
      'Sin',
      'Wong',
      'Satana',
      'Singularity',
      'X-23',
      'America Chavez',
      'Jessica Jones',
      'Ms. Marvel',
      'She-Hulk',
      'Squirrel Girl',
      'Vulture',
      'Destroyer',
      'White Tiger',
      'Luna Snow',
      'Absorbing Man',
      'Misty Knight',
      'Sandman',
      'Whiplash',
      'Medusa',
      'Ironheart',
      'Valkyrie',
      'Quasar',
    ];
    $this->name = $names[array_rand($names)];
  }

  /**
   * @return mixed
   */
  public function getHealth () {
    return $this->health;
  }

  /**
   * @param $health
   */
  public function setHealth ($health) {
    $this->health = $health;
  }

  /**
   * @return mixed
   */
  public function getArmour () {
    return $this->armour;
  }

  /**
   * @return mixed
   */
  public function getDamage () {
    return $this->damage;
  }

  /**
   * @return bool
   */
  public function miss () {
    return $this->missPercent >= rand(1, 50);
  }

  /**
   * @return bool
   */
  public function isLucky () {
    $randomLuck = rand(0, 100);
    if ($this->luck > $randomLuck) {
      return true;
    }
    return false;
  }

  /**
   * @return bool
   */
  public function hasSpecialSkills () {
    if (isset($this->specialSkills)) {
      return true;
    }
    return false;
  }

  /**
   * Randomly soldier will get lucky in defense and will use specialSkill
   *
   * @return bool
   */
  public function defenseLuck () {
    foreach ($this->specialSkills['defense'] as $name => $skill) {
      $randomLuck = rand(0, 100);
      if (($skill['chance'] > $randomLuck) && $skill['type'] == 'passive') {
        return $skill;
      }
    }
    return false;
  }

  /**
   * @return bool
   */
  public function attackLuck () {
    foreach ($this->specialSkills['attack'] as $skill) {
      $randomLuck = rand(0, 100);
      if (($skill['chance'] > $randomLuck) && $skill['type'] == 'passive') {
        return $skill;
      }
    }
    return false;
  }
}
