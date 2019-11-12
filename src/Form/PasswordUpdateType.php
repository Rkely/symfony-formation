<?php

namespace App\Form;


use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PasswordUpdateType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('accienMotDePasse',TextType::class, $this->getinformation("Ancien mot de passe","tapez votre ancien mot de passe"))
            ->add('nouveauMotDePasse', TextType::class,$this->getinformation("Nouveau mot de passe","tapez votre nouveua mot de passe"))
            ->add('confirmNouveauMotDePasse',TextType::class, $this->getinformation("Nouveau mot de passe","tapez votre nouveua mot de passe"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
