<?php

namespace App\Form;

use App\Entity\Comment;
use App\Form\ApplicationType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rating', IntegerType::class, $this->getinformation("Notes sur 5", 
            "Veullez mettre votre notes",[
                'attr'=>[
                    'min' =>0,
                    'max' =>5,
                    'step' =>1
                ]
            ]))
            ->add('content', TextareaType::class, $this->getinformation("Votre avis / Témoignage",
            "Veullez mettre votre comentaire et soyez très precis cela aidera nos futurs voyageurs !! "));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
