<?php


namespace App\Controller;


use App\Entity\Decision;
use App\Form\ContributorType;
use App\Form\DecisionType;
use App\Repository\ContributorRepository;
use App\Repository\DecisionRepository;
use App\Repository\DocumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/contributor")
 * Class ContributorController
 * @package App\Controller
 */

class ContributorController extends  AbstractController
{
    /**
     * @Route("/index/{id}", name="contributor_index_id")
     * @param ContributorRepository $contributorRepository
     * @return Response
     */
    public function index(EntityManagerInterface $manager,ContributorRepository $contributorRepository, $id,DecisionRepository $decisionRepository,Request $request): Response{
        $contributor = $contributorRepository->find($id);

        $decisions = $decisionRepository->allDecisionsNotTaken($id);
            //dump($decisions); die;
        /**
         * Formulaire reliés aux décisions à prendre par le contributor
         *  1* Changement des décisions que par ceux non prises
         *  2* Affichage du formulaire
         *  3* Traitement des résultats relatifs  aux décisions
         */
        foreach ($contributor->getDecisions() as $decision) {
            $contributor->removeDecision($decision);
        }
        foreach ($decisions as $decision){
            $contributor->addDecision($decision);
        }
        $form = $this->createForm(ContributorType::class,$contributor);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            /**
             * Updating each decision with Form data
             */
                                                    // dump($form->getData());
            $decisions=$contributor->getDecisions();
                                                    //dump($decisions);die;
            foreach ($decisions as $decision)
                                switch ($decision->getDeposit()){
                                                 case 'oui' : $decision->setIsTaken(true);$decision->setContent('Dépôt');break;
                                                 case 'non' : $decision->setIsTaken(false);$decision->setContent('Refus Dépôt');break;
                                                 default    : //$decision->setIsTaken(null);
                                                             $decision->setContent('En attente');break; // a voir
                                    }
            /**
             * Saving the contributor's decisions
             */
              //$manager->persist($contributor);
            $manager->flush();
            $this->addFlash('success','Les nouvelles décisions sont prises en compte');
            return $this->redirectToRoute('contributor_index_id',[
                                                                        'id' => $contributor->getId()
                                                    ]);
        }
        return $this->render('contributor/index.html.twig',
                            [
                                'contributor' => $contributor,
                                'decisions' => $decisions,
                                'form' => $form->createView(),

                            ]);
    }
}