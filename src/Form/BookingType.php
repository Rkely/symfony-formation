<?php

namespace App\Form;

use App\Entity\Booking;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\dataTransformer\FrenchToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BookingType extends ApplicationType
{
    private $tranformer;
    public function __construct( FrenchToDateTimeTransformer $tranformer)
    {
       $this->transformer = $tranformer; 
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('startDate', TextType::class, $this->getinformation("date d'arrive", "Mettez votre date d'arrive"))
        ->add('endDate', TextType::class, $this->getinformation("date de depart","votre date de depart"))
        ->add('comment', TextareaType::class, $this->getinformation("Vous avez un commentaire","Si vous avez un commentaire n'hesitez pas à le mettre",["required"=> false]));
        $builder->get('startDate')->addModelTransformer($this->transformer);
        $builder->get('endDate')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
            'validation_groups'=>[
                'Default',
                'front'
            ]
        ]);
    }
}
