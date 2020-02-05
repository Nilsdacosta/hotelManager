<?php

namespace App\Form;

use App\Entity\Chambre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ChambreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('capacite')
            ->add('etat' , ChoiceType::class, [
                'choices'  => [
                    'A blanc' => 1,
                    'Recouche' => 2,
                    'Prête' => 3,
                    'Hors Service' =>4
                    
                ],
                'expanded' => true
            ])
            ->add('description')
            ->add('prix')
            ->add('nom')
            ->add('tva', ChoiceType::class, [
                'choices'  => [
                    '10%' => 1,
                    '20%' => 2,  
                ],
                'expanded' => true
            ])
           // ->add('reservations')
           // ->add('assignationMenage')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Chambre::class,
        ]);
    }
}
