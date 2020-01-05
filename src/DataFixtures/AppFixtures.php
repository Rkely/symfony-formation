<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Booking;
use App\Entity\Comment;
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
        
        $faker = Factory::create('FR_fr');
        $adminrole = new Role();
        $adminrole->setTitle('ROLE_ADMIN');
        $manager->persist($adminrole);

        $useradmin = new User();
        $useradmin->setFirstName('TELLY')
                  ->setLastName('Issa')
                  ->setEmail('tellyissa@gmail.com')
                  ->setPicture('https://secure.gravatar.com/avatar/fa24afd4f58c7b6ffe0e2c8c58659681?s=64')
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
        for($i =0 ; $i< 50 ;$i++){
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
        for($a = 0; $a< mt_rand(1, 10) ; $a++){
            $booking = new Booking();
            $createAt = $faker->dateTimeBetween('-6 months');
            $startDate = $faker->dateTimeBetween('-3 months');
            $jour = mt_rand(3, 11);
            $endDate = (clone $startDate)->modify("+$jour days");
            $montant = $jour * $Ad->getPrice();
            $booker = $tabuser[mt_rand(0, count($tabuser)-1)];
            $comment  = $faker->paragraph();
            $booking->setBooker($booker)
                    ->setCreatedAt($createAt)
                    ->setEndDate($endDate)
                    ->setStartDate($startDate)
                    ->setAmount($montant)
                    ->setComment($comment)
                    ->setAd($Ad);
                $manager->persist($booking);
                // faire des commentaire 
                 
                if(mt_rand(0,1)){
                    $comment = new Comment();
                    $comment->setContent($faker->paragraph())
                            ->setAuthor($booker)
                            ->setRating(mt_rand(2,5))
                            ->setAd($Ad);
                            $manager->persist($comment);
                }
                
                     
        }

        }
        $manager->flush();
    }
}
