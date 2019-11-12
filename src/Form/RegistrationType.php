<?php

namespace App\Form;

use App\Entity\User;
use App\Form \ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName',TextType::class, $this->getinformation("Prénom","Entre votre prenom...."))
            ->add('lastName',TextType::class, $this->getinformation("Nom","Entre votre nom...."))
            ->add('email', EmailType::class, $this->getinformation("Email","Saississez votre email"))
            ->add('picture',UrlType::class, $this->getinformation("Gravatar","Url de votre image"))
            ->add('hash',PasswordType::class, $this->getinformation("Mot de passe","Choississez un bon mot de passe"))
            ->add('passwordConfirm',PasswordType::class, $this->getinformation("Confirmer Mot de passe","Confirmer mot de passe"))
            ->add('introduction',TextType::class, $this->getinformation("Introduction","Decrivez vous en quelque mot"))
            ->add('description',TextareaType::class, $this->getinformation("Description","Parlez en sur vous..."))
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
