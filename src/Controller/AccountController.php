<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpade;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="account_login")
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
        return $this->render('account/login.html.twig', [
            'haserror' => $error !==null,
            'username' => $username
        ]);
    }
     /**
     * @Route("/deconnexion", name="account_logout")
     */
    public function logout(){}
     /**
     * @Route("/Inscription", name="account_inscription")
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder){
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                "Votre compte à bien éte cree!!!!"
            );
           return $this->redirectToRoute('account_login');
        }
        return $this->render('account/registration.html.twig',
    ['form' => $form->createView()
    ]);
    }
    /**
     * @Route("/compte/profile", name="account_profile")
     * @IsGranted("ROLE_USER")
     */
    public function profile(Request $request, EntityManagerInterface $manager){
        $user = $this->getUser();
        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                "Votre compte à ete mis à jour!!!!"
            );
        }
        return $this->render('account/profile.html.twig',
        [
            'form' => $form->createView()
        ]);
    }
     /**
      * permet de voir un user
     * @Route("/mycompte", name="my_compte")
     * @IsGranted("ROLE_USER")
     */
    public function mycompte()
    {
        return $this->render('user/index.html.twig', [
            'user' => $this->getUser(),
        ]);
    }
     /**
     * @Route("/change/password", name="password_update")
     * @IsGranted("ROLE_USER")
     */
    public function changemotdepasse(Request $request, EntityManagerInterface $manager,UserPasswordEncoderInterface $encoder){
        $user = $this->getUser();
        $motdepasse = new PasswordUpade();
        $form = $this->createForm(PasswordUpdateType::class, $motdepasse);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if(!password_verify($motdepasse->getAccienMotDePasse(),$user->getHash())){
                $form->get('accienMotDePasse')->addError( new FormError("Le mot de passe que vous avez taper
                 n'est pas votre mot de passe actuel !!!"));
            }else
            {
                $hash = $encoder->encodePassword($user, $motdepasse->getNouveauMotDePasse());
                $user->setHash($hash);
                $manager->persist($user);
                $manager->flush();
                return $this->redirectToRoute('voirs_annonces');
            }
           
            $this->addFlash(
                'success',
                "Votre mot de passe à ete mis à jour!!!!"
            );
        }
        return $this->render('account/passwordUpdate.html.twig',
        [
            'form' => $form->createView()
        ]);
    }
    /**
     * Permet au user de voir des reservation
     * @Route("/account/bookings", name="account_bookings")
     *
     * @return Response
     */
    public function bookings(){
        return $this->render('account/bookings.html.twig');
    }
}
