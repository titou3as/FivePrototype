<?php


namespace App\Controller;


use App\Entity\Decision;
use App\Form\DecisionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
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
     * @Route("/command",name="command_test")
     * @param KernelInterface $kernel
     * @return Response
     */
    public function execution(KernelInterface $kernel):Response{
        $application = new Application($kernel);
        $application->setAutoExit(false);
        $input = new ArrayInput([
            'command'=>'decision:create',
            '--document'=>8,
            '--contributor'=>8
        ]);
        $output = new BufferedOutput();$application->run($input,$output);
        $content = $output->fetch();
        return new Response($content);
    }
}