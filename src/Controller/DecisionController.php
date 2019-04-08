<?php


namespace App\Controller;


use App\Entity\Decision;
use App\Form\DecisionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/decision")
 * Class DecisionController
 * @package App\Controller
 */
class DecisionController extends AbstractController
{
    /**
     * @Route("/deposit", name="decision_deposit")
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deposit(EntityManagerInterface $manager,Request $request) : Response{
        $decision = new Decision();
        $form = $this->createForm(DecisionType::class,$decision);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($decision);
            $manager->flush();
        }
        return $this->render('decision/create.html.twig',[
            'form' => $form->createView()
        ]);
    }
}