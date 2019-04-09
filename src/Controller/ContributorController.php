<?php


namespace App\Controller;


use App\Entity\Contributor;
use App\Entity\Decision;
use App\Form\ConnexionContributorType;
use App\Form\ContributorType;
use App\Form\DecisionsNotTakenType;
use App\Form\DecisionType;
use App\Repository\ContributorRepository;
use App\Repository\DecisionRepository;
use App\Repository\DocumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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

        $decisions = $contributor->getDecisions()->filter(function ($decision){
            return $decision->getIsTaken()==false;
        });


           // dump($decisions); die;
        /**
         * Formulaire reliés aux décisions à prendre par le contributor
         *  1* Changement des décisions que par ceux non prises
         *  2* Affichage du formulaire
         *  3* Traitement des résultats relatifs  aux décisions
         */
        //$decisionsNT = $decisionRepository->getAllDecisionsNotTaken($contributor->getId());

       // dump($contributor->getDecisions());dump($decisions);dump(($decisionsNT));die;

        $form = $this->createForm(ContributorType::class,$contributor,['data' => $decisions]);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            /**
             * Updating each decision with Form data
             */
                                                  // dump($form->getData());
                                                    //dump($decisions);die;
            foreach ($decisions as $decision)
                                switch ($decision->getDeposit()){
                                                 case 'oui' : $decision->setIsTaken(true);$decision->setContent('Dépôt');break;
                                                 case 'non' : $decision->setIsTaken(true);$decision->setContent('Refus Dépôt');break;
                                                 default    : //$decision->setIsTaken(null);
                                                             $decision->setContent('En attente');break;
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


    /**
     * @Route("/login", name = "contributor_connexion")
     */
    public function connexion(ContributorRepository $repository, Request $request): Response
    {

        $form = $this->createForm(ConnexionContributorType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $data = $form->getData();
            $login = $data->getLogin();
            $pwd = $data->getPwd();
            /**
             *Searching for contributor's having login && Pwd into the database
             * @var $contributor Contributor
             */
            $contributor = $repository->findOneBy([
                'login' => $login,
                'pwd'   => $pwd
            ]);
            if($contributor!==null){
                $this->addFlash('success','Authentification réussite');
                return $this->redirectToRoute('contributor_index_id',[
                    'id' => $contributor->getId()
                ]);
            }
        }
        return $this->render('contributor/connexion.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/logout", name="contributor_logout")
     * @return Response
     */
    public function deconnexion(): Response{
        $this->addFlash('success','Vous êtes déconnecté');
        return $this->render('home/index.html.twig');
    }


    /**
     * @Route("/not/{id}", name="contributor_show_not")
     * @param EntityManagerInterface $manager
     * @param ContributorRepository $contributorRepository
     * @param $id
     * @param DecisionRepository $decisionRepository
     * @param Request $request
     * @return Response
     */
    public function home(EntityManagerInterface $manager,ContributorRepository $contributorRepository, $id,DecisionRepository $decisionRepository,Request $request): Response{
        $contributor = $contributorRepository->find($id);
        $decisionsNT= $contributorRepository->findContributorNT($id);

        /**
         * Formulaire reliés aux décisions à prendre par le contributor
         *  1* Changement des décisions que par ceux non prises
         *  2* Affichage du formulaire
         *  3* Traitement des résultats relatifs  aux décisions
         */
        //$decisionsNT = $decisionRepository->getAllDecisionsNotTaken($contributor->getId());

        // dump($contributor->getDecisions());dump($decisions);dump(($decisionsNT));die;

      //  $form = $this->createForm(ContributorType::class,$contributor,['empty_data' => $decisionsNT]);

        $form = $this->createFormBuilder($decisionsNT)
            ->add('decisions', CollectionType::class,
                [
                    'compound' =>false,
                    'label' => false,
                    'required' =>false,
                    'entry_type'=> DecisionType::class,
                    'empty_data' => $decisionsNT
                ])
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted()){
            /**
             * Updating each decision with Form data
             */
            // dump($form->getData());
            //dump($decisions);die;
           dump($form->getData());
            foreach ($decisionsNT as $decision)
                switch ($decision->getDeposit()){
                    case 'oui' : $decision->setIsTaken(true);$decision->setContent('Dépôt');break;
                    case 'non' : $decision->setIsTaken(true);$decision->setContent('Refus Dépôt');break;
                    default    : //$decision->setIsTaken(null);
                        $decision->setContent('En attente');break;
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
                'decisions' => $decisionsNT,
                'form' => $form->createView(),

            ]);
    }
}