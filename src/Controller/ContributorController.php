<?php


namespace App\Controller;


use App\Form\ContributorType;
use App\Repository\ContributorRepository;
use App\Repository\DecisionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function index(ContributorRepository $contributorRepository, $id,DecisionRepository $decisionRepository): Response{
        $contributor = $contributorRepository->find($id);

        $decisions = $decisionRepository->allDecisionsNotTaken($id);
            //dump($decisions); die;
        /**
         * Formulaire reliés aux décisions à prendre par le contributor
         *  1* Changement des décisions que par ceux non prises
         *  2* Affichage du formulaire
         *  3* Résultats des décisions
         */
        foreach ($contributor->getDecisions() as $decision) {
            $contributor->removeDecision($decision);
        }
        foreach ($decisions as $decision){
            $contributor->addDecision($decision);
        }
        $form = $this->createForm(ContributorType::class,$contributor);
        return $this->render('contributor/index.html.twig',
                            [
                                'contributor' => $contributor,
                                'decisions' => $decisions,
                                'form' => $form->createView(),

                            ]);
    }
}