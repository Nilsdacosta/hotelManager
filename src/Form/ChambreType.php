<?php

namespace App\Form;

use App\Entity\Tva;
use App\Entity\Chambre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ChambreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('capacite', ChoiceType::class, [
                'choices'  => [
                    'Double' => 'Double',
                    'Single' => 'Single',
                    'Twin' => 'Twin',
                    'Deluxe' => 'Deluxe',
                    'Suite' => 'Suite',


                ],
                'expanded' => true
            ])
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
            ->add('tva', EntityType::class, [
                'class'=> Tva::class ,
                'choice_label' => ' nom',
                'expanded' => true,
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
