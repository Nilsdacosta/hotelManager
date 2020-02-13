<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ClientType extends AbstractType
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
            ->add('adresse', TextType::class,[
                'required'=>true,
            ])
            ->add('codePostal', TextType::class,[
                'required'=>true,
            ])
            ->add('ville', TextType::class,[
                'required'=>true,
            ])
            ->add('telephone', TextType::class,[
                'required'=>true,
            ])
            ->add('mail', EmailType::class,[
                'required'=>false,
            ])
            ->add('dateDeNaissance',DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker',
                
                ],
                'required'=>false
            ])
            ->add('commentaire', TextType::class,[
                'required'=>false,
            ])
            ->add('enregistrer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
