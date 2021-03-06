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
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ReservationClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateEntree',DateType::class, [
                'widget' => 'single_text',
                'label'=> 'Date d\'arrivée',
                'attr' => ['class' => 'js-datepicker',

                ]
            ])

            ->add('dateSortie',DateType::class, [
                'widget' => 'single_text',
                'label'=> 'Date de départ',
                'attr' => ['class' => 'js-datepicker',

                ]
            ])

            ->add('carteBancaire',IntegerType::class,[
                'required'=> false
            ])

            ->add('client', EntityType::class,
                ['class' => Client::class,
                'label'=> 'Client',
                'choice_label' => function ($client) {
                    return $client->getNom().' - '.$client->getPrenom().' - '.$client->getTelephone().' - '.$client->getMail();}
            ])

            ->add('chambre', EntityType::class,[
                'class' => Chambre::class,
                'label'=> 'Chambre',
                'choice_label' => 'nom',
                'multiple'=> true
            ])

            ->add('optionService', EntityType::class,[
                'class' => OptionService::class,
                'choice_label' => ' nomOption',
                'label'=> 'Option de service',
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
