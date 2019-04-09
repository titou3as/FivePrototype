<?php

namespace App\Form;

use App\Entity\Contributor;
use App\Entity\Decision;
use App\Repository\ContributorRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DecisionsNotTakenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('decisions',CollectionType::class,[
                'label' => false,
                'required' =>false,
                'entry_type'=> DecisionType::class,
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contributor::class,
        ]);
    }
}



