<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i =0 ; $i< 10 ;$i++){
        $faker = Factory::create();
        $Ad = new Ad();
        $title = $faker->sentence;
        $slug = $title;
        $coverImage =$faker->imageUrl(100,100);
        $introduction = $faker->paragraph();
        $content = '<p>'. join('<p></p>',$faker->paragraphs(2)) .'</p>';

         $Ad->setTitle($title)
            ->setCoverImage($coverImage)
            ->setIntroduction($introduction)
            ->setContent($content)
            ->setPrice(mt_rand(50000,100000))
            ->setRooms(mt_rand(1,5));

        $manager->persist($Ad);
        for($j = 0; $j < 4 ; $j ++){
            $image = new Image();
            $faker = Factory::create();
            $image -> setUrl($faker->imageUrl(600,400))
                -> setLegende($faker->sentence())
                ->setAd($Ad);
                $manager ->persist($image);
        }

        }
        $manager->flush();
    }
}
