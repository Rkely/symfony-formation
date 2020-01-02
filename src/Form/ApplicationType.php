<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

 class ApplicationType extends AbstractType{
     protected function getinformation($titre, $placeholder, $option=[]){
        return array_merge_recursive([
            'label' => $titre,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ], $option);
    }
 }
