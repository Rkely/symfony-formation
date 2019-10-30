<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Image;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdController extends AbstractController
{
    /**
     * @Route("/voirs", name="voirs_annonces")
     */
    public function index(AdRepository $rep)
    {
        $ads = $rep->findAll();
        return $this->render('ad/index.html.twig', [
            'ads' => $ads
        ]);
    }
    /**
     *@Route("/ad/{slug}/edit", name="edite_article")
     *
     * @return Response
     */
    public function edit(Ad $ad, ObjectManager $manager, Request $request){
        $form = $this->createForm(AdType::class, $ad);

         $form->handleRequest($request);
         if($form->isSubmitted()&& $form->isValid()){
             foreach($ad->getImages() as  $image){
                 $image->setAd($ad);
                 $manager->persist($image);
             }
             $ad=$form->getData();
             $manager->persist($ad);
             $manager->flush();
             
             return $this->redirectToRoute('voir_annonce',[
                 'slug' => $ad->getSlug()
                 ]);
                 $this->addFlash(
                     'succes',
                     "les  Mofication ont ete effectuer"

                 );

         }
         

        return $this->render('ad/edit.html.twig',[
            'form'=>$form->createView(),
            'ad'=>$ad
        ]);
    }

     /**
     * @Route("/ad/new", name="nouveau_annonce")
     * 
     * @return Response
     */
    public function new(Request $request, ObjectManager $manager)
    {
        $ad = new Ad();
         $form = $this->createForm(AdType::class, $ad);
         $form->handleRequest($request);
         //$this->addFlash('success','Votre article à bien été enregistrer');
         if($form->isSubmitted() && $form->isValid()){
             foreach($ad->getImages() as $image){
                 $image->setAd($ad);
                 $manager->persist($image);
             }
             $ad=$form->getData();
             $manager->persist($ad);
             $manager->flush();
             
             return $this->redirectToRoute('voir_annonce',[
                 'slug' => $ad->getSlug()
                 ]);
         }
        
        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);
        
    }
    /**
     * @Route("/ad/{slug}", name="voir_annonce")
     * 
     * @return Response
     */
    public function voir(Ad $ad)
    {
        
        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);
        
    }
   
}
