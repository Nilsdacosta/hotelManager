<?php

namespace App\Form;

use App\Entity\OptionService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class OptionServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomOption')
            ->add('dateCreation')
            ->add('prixOption')
            //->add('employe')
            // //->add('tva', ChoiceType::class, [
            //     'choices'  => [
            //         '20' => 20,
            //         '5' => 5,
                   
            //     ],
            //     'expanded' => true
            // ])
            // ->add('reservations')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OptionService::class,
        ]);
    }
}
