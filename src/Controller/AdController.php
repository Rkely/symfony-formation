<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Entity\Image;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    
     /**
     * @Route("/ad/new", name="nouveau_annonce")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $manager)
    {
        $ad = new Ad();
         $form = $this->createForm(AdType::class, $ad);
         $form->handleRequest($request);
         if($form->isSubmitted() && $form->isValid()){
             foreach($ad->getImages() as $image){
                 $image->setAd($ad);
                 $manager->persist($image);
             }
             $ad->setAuthor($this->getUser());
             $manager->persist($ad);
             $manager->flush();
            // $this->addFlash('success','Votre article à bien été enregistrer');
                 
             return $this->redirectToRoute('voir_annonce',[
                 'slug' => $ad->getSlug()
                 ]);
         }
        
        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);
        
    }
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
     *@Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message ="vous n'etez pas le proprietaire")
     * @return Response
     */
    public function edit(Ad $ad, EntityManagerInterface $manager, Request $request){
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
    /**
     * Undocumented function
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()")
     *@Route("article/supprimer/{slug}", name="supprimer_article")
     * @param Ad $ad
     * @param EntityManagerInterface $manager
     * @return void
     */
    public function delete_article(Ad $ad, EntityManagerInterface $manager){
        $manager->remove($ad);
        $manager->flush();

        $this->addFlash(
            'success',
            "Votre annonce <strong>{ $ad->getTitle() }</strong>  a bien ete supprimer"
        );

        return $this->redirectToRoute('voirs_annonces');

    }
   
}
