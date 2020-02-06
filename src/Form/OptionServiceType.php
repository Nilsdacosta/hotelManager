<?php

namespace App\Form;

use App\Entity\Tva;
use App\Entity\OptionService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class OptionServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomOption')
            //->add('dateCreation')
            ->add('prixOption')
            //->add('employe')
            ->add('tva', EntityType::class, [
                'class'=> Tva::class ,
                'choice_label' => ' nom',
                  'expanded' => true,
             ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OptionService::class,
        ]);
    }
}
