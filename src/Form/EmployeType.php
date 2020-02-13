<?php

namespace App\Form;

use App\Entity\Employe;
use PhpParser\Parser\Multiple;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class EmployeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('nom', TextType::class,[
                'required'=>true,
            ])
            ->add('prenom', TextType::class,[
                'required'=>true,
                
            ])
            ->add('telephone', TextType::class,[
                'required'=>true,
            ])
            ->add('poste', ChoiceType::class, [
                'choices'  => [
                    'Poste occupé' => 'Choose an option',
                    'Directeur' => 1,
                    'Receptionniste' => 2,
                    'Gouvernante' => 3,
                    'Femme de chambre'=>4,
                    'Stagiaire'=>5
                    
                ],
            ])
            ->add('roles', ChoiceType::class, [
                 'choices'  => [
                    'Rôle' => 'Choose an option',
                    'ROLE_USER' => 3,
                    'ROLE_ADMIN' => 2,
                    'ROLE_SUPER_ADMIN' => 1,
                   
                ],
                'mapped' => false,
            ])
           // ->add('password', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                //'mapped' => false,
                //'constraints' => [
                //     new NotBlank([
                //         'message' => 'Please enter a password',
                //     ]),
                //     new Length([
                //         'min' => 6,
                //         'minMessage' => 'Your password should be at least {{ limit }} characters',
                //         // max length allowed by Symfony for security reasons
                //         'max' => 4096,
                //     ]),
                // ],
            //])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
        ]);
    }
}
