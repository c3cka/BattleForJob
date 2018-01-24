<?php
/**
 * Created by PhpStorm.
 * User: igorkos
 * Date: 24/01/18
 * Time: 10:26
 */
 namespace App\Controller;

 use App\Functions\Validator;
 use App\Functions\SoldierCreator;
 use App\Entities\Army;
 use App\Game\Start;
 use Symfony\Component\HttpFoundation\Request;
 use Symfony\Bundle\FrameworkBundle\Controller\Controller;

 class FightController extends Controller {
   
   public function index(Request $request, Validator $validator, SoldierCreator $generator)
   {
     $numOfSoldiersArmy1 = $request->query->get('army1');
     $numOfSoldiersArmy2 = $request->query->get('army2');

     // Validate that params are numeric
     if (!$validator->validate([$numOfSoldiersArmy1, $numOfSoldiersArmy2])) {
       return $this->render('index.html.twig', ['error' => 'Please enter number of soldiers for each army!']);
     }

     // Generate soldiers
     $soldiers1 = $generator->generateSoldiers('C3cK@s', $numOfSoldiersArmy1);
     $soldiers2 = $generator->generateSoldiers('Degordians', $numOfSoldiersArmy2);

     // Create army
     $army1 = new Army('C3cK@s', $soldiers1);
     $army2 = new Army('Degordians', $soldiers2);

     // Battle
     $battle = new Start($army1, $army2);
     $result = $battle->start();


     return $this->render('index.html.twig', ['winner' => $result['winner']->getName(),
       'soldiersCount' => $result['winner']->getSoldierCount(), 'turns' => $result['totalTurns']]);
   }
 }
