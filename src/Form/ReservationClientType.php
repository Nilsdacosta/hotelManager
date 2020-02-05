<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Chambre;
use App\Entity\Reservation;
use App\Entity\OptionService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ReservationClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateEntree',DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker'
                ]
            ])
            ->add('dateSortie',DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker'
                ]
            ])
            ->add('carteBancaire')
            ->add('client', EntityType::class,
            ['class' => Client::class,
            'choice_label' => 'nom',
            ])
            ->add('chambre', EntityType::class,[
                'class' => Chambre::class,
                'choice_label' => 'nom',

            ])
            ->add('optionService', EntityType::class,[
                'class' => OptionService::class,
                'choice_label' => ' nomOption',
                'multiple'=> true,
                'expanded' =>true
            ])

            ->add('enregistrer', SubmitType::class)
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
