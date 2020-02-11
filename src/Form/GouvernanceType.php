<?php

namespace App\Form;

use App\Entity\Chambre;
use App\Entity\Employe;
use App\Entity\OptionService;
use App\Entity\AssignationMenage;
use App\Repository\EmployeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GouvernanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $poste = $options['poste'];

        $builder
            // ->add('date')
            ->add('employe', EntityType::class, [
                'class' => Employe::class,
                // Je crée une query qui va me permetre de n'afficher que les employé dont le poste à la valeur 3 => ménage
                // Je force sa valeur dans le resolver pour ne pas avoir de conflit dans le controller.
                'query_builder' => function (EmployeRepository $employe) use ($poste){
                    return $employe->createQueryBuilder('poste')
                        ->andWhere('poste.poste = :val')
                        ->setParameter('val', $poste)
                        ;
                },
                'choice_label' => 'username',

            ])
            ->add('optionService', EntityType::class, [
                'class' => OptionService::class,
                'choice_label' => 'nomOption',
                'multiple' => true,
                'expanded' => true
            ])
            // ->add('chambre', EntityType::class, [
            //     'class' => Chambre::class,
            //     'choice_label' => 'nom',
            //     'expanded' =>true
            // ])
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AssignationMenage::class,
            'poste' => 4
        ]);
    }
}
