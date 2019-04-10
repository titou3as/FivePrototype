<?php

namespace App\Form;

use App\Entity\Contributor;
use App\Entity\Decision;
use App\Repository\ContributorRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DecisionsNotTakenType extends AbstractType
{
/*
    private  $decisionsnt;
    public function __construct(ContributorRepository $contributorRepository)
    {
        $this->decisionsnt = $contributorRepository->findContributorNT(1);
    }
*/
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            /*
            ->add('decisions',CollectionType::class,[
                'label' => false,
                'required' =>false,
                'entry_options'=>['id' =>'hhh'],
                'entry_type'=> DecisionType::class,
            ])
            */
        ->add('decisions',EntityType::class,[
            'attr' =>['id'=>'decisionnn'],
            'query_builder'=>function(ContributorRepository $contributorRepository){
            return $contributorRepository->findContributorNT(1);
            }
        ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contributor::class,
        ]);
    }
}



