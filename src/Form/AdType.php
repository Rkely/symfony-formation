<?php

namespace App\Form;

use App\Entity\Ad;

use App\Form\ImageType;
use App\Form \ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title',TextType::class, $this->getinformation("Titre","Mettez un titre attirant"))
        ->add('slug',TextType::class, $this->getinformation("Slog","votre slog sera genere de facon automatique",
        ['required' => false]))
        ->add('price', MoneyType::class,  $this->getinformation("Prix par nuit","Mettez votre prix"))
        ->add('introduction',TextType::class, $this->getinformation("Introduction","Mettez une description annonce"))
        ->add('content', TextareaType::class, $this->getinformation("Description","Mettez un contenu"))
        ->add('coverImage',UrlType::class, $this->getinformation("Image","Mettez une image attirant"))
        ->add('rooms',IntegerType::class,$this->getinformation("Nombre de chambres","Mettez le nombre de chambres"))
        ->add('images', CollectionType::class,
        [
            'entry_type'=>ImageType::class,
            'allow_add' =>true,
            'allow_delete' =>true
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
