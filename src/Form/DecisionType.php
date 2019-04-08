<?php

namespace App\Form;

use App\Entity\Decision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DecisionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           // ->add('isTaken')
         //   ->add('content')
         //   ->add('document')
         //   ->add('contributor')
            ->add('deposit',ChoiceType::class,['label'=> 'Voulez-vous déposer ?',
                'expanded' => true,
                'choices'=>[
                    'Je veux déposer'=> 'oui',
                    'Je ne veux pas' => 'non',
                    'Je ne sais pas encore' => 'wait'
                ],
           ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Decision::class,

        ]);
    }
}
