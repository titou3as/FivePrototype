<?php


namespace App\Controller;


use App\Entity\Decision;
use App\Form\DecisionType;
use App\Repository\DecisionRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
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

    /**
     * @Route("/edit/{id}", name="decision_edit")
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     * @param Decision $decision
     */
    public function edit(Decision $decision, $id, DecisionRepository $repository,EntityManagerInterface $manager, Request $request): Response{

        $decision = $repository->find($id);
        dump($decision);
        $form = $this->createForm(DecisionType::class,$decision);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $manager->flush();
            }
            return $this->render('decision/edit.html.twig',[
                'decision' => $decision,
                'form' => $form->createView()
            ]);
        }
}