<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    // Constructeur pour faire appel encoder
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');
        $adminrole = new Role();
        $adminrole->setTitle('ROLE_ADMIN');
        $manager->persist($adminrole);

        $useradmin = new User();
        $useradmin->setFirstName('TELLY')
                  ->setLastName('issa')
                  ->setEmail('tellyissa@gmail.com')
                  ->setPicture('https://avatars.io/twitter/LiiorC')
                  ->setHash($this->encoder->encodePassword($useradmin,'password'))
                  ->setIntroduction($faker->sentence())
                  ->setDescription('<p>'. join('<p></p>',$faker->paragraphs(2)) .'</p>')
                  ->addUserRole($adminrole);
                   $manager->persist($useradmin);
        $tabuser=[];
        $genres = ['male','female'];
        for($i =0 ; $i<10 ; $i++){
            $user = new User();
            $genre = $faker->randomElement($genres);
            $picture = "https://randomuser.me/api/portraits/";
            $pictureId = mt_rand(1,99).'.jpg';
            $picture .= ($genre=='male' ? 'men/' : 'women/').$pictureId;
            $hash = $this->encoder->encodePassword($user,'password');
            $content = '<p>'. join('<p></p>',$faker->paragraphs(2)) .'</p>';
            $user->setFirstName($faker->firstName($genre))
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setPicture($picture)
                ->setHash($hash)
                ->setIntroduction($faker->sentence())
                ->setDescription($content);
            $manager->persist($user);
            $tabuser[]=$user;

        }
        for($i =0 ; $i< 10 ;$i++){
        $Ad = new Ad();
        $title = $faker->sentence;
        $slug = $title;
        $coverImage =$faker->imageUrl(100,100);
        $introduction = $faker->paragraph();
        $content = '<p>'. join('<p></p>',$faker->paragraphs(2)) .'</p>';

            $user = $tabuser[mt_rand(0, count($tabuser)-1)];

         $Ad->setTitle($title)
            ->setCoverImage($coverImage)
            ->setIntroduction($introduction)
            ->setContent($content)
            ->setPrice(mt_rand(50000,100000))
            ->setRooms(mt_rand(1,5))
            ->setAuthor($user);

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
