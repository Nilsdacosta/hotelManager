<?php

namespace App\Form;

use App\Entity\Chambre;
use App\Entity\Employe;
use App\Entity\OptionService;
use App\Entity\AssignationMenage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GouvernanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date')
            ->add('employe', EntityType::class, [
                'class' => Employe::class,
                'choice_label' => 'username',
                'expanded' =>true
            ])
            ->add('optionService', EntityType::class, [
                'class' => OptionService::class,
                'choice_label' => 'nomOption',
                'expanded' => true
            ])
            ->add('chambre', EntityType::class, [
                'class' => Chambre::class,
                'choice_label' => 'nom',
                'expanded' =>true
            ])
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AssignationMenage::class,
        ]);
    }
}
