<?php

namespace App\Form;

use App\Entity\Contributor;
use App\Repository\ContributorRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContributorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
         //   ->add('login')
         //   ->add('pwd')
            ->add('decisions',CollectionType::class,[
                'label' => false,
             'required' =>false,
             'entry_type'=> DecisionType::class,
         ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contributor::class,

        ]);
    }

}

