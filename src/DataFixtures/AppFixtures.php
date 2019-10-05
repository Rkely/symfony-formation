<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
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
        $introduction = $faker->paragraph(2);
        $content = '<p>'. join('<p></p>',$faker->paragraphs(4)) .'</p>';

         $Ad->setTitle($title)
            ->setCoverImage($coverImage)
            ->setIntroduction($introduction)
            ->setContent($content)
            ->setPrice(mt_rand(1,100))
            ->setRooms(mt_rand(1,5));

        $manager->persist($Ad);

        }
        $manager->flush();
    }
}
