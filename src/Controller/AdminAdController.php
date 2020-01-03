<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;

use App\Service\PaginationService;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_index")
     */
    public function index(AdRepository $repository,$page, PaginationService $pagination)
    {
        $pagination->setEntityClass(Ad::class)
                    ->setPage($page);
        return $this->render('admin/ad/index.html.twig', [
            'pagination' =>$pagination
        ]);
    }
    /**
     * permet d'afficher le formulaire d'edition
     * @Route("admin/ads/{id}/edit", name="admin_ads_edit")
     * @param Ad $ad
     * @return Response
     */
    public function edit(Ad $ad, Request $request,  ObjectManager $manager){
        $form =$this->createForm(AdType::class, $ad);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($ad);
            $manager->flush();
            $this->addFlash(
                'success',
                "Modification de l'annonce <strong>{$ad->getTitle()}</strong> à ete effectue"
            );
        }
        return $this->render('admin/ad/edit.html.twig',[
            'ad'=> $ad,
            'form'=>$form->createView()
        ]);
    }
    /**
     * permet de supp
     * @Route("/admin/ads/{id}/delete", name="admin_ads_delete")
     *
     * @param Ad $ad
     * @return void
     */
    public function delete(Ad $ad, ObjectManager $manager){
        if(count($ad->getBookings())>0){
            $this->addFlash(
                'warning',
                "Vous ne pouvz pas supprimer l'annonce car elle possède déja des reservation !"
            );
        }else{
            $manager->remove($ad);
            $manager->flush();
            $this->addFlash(
                'success',
                "L'annonce a bien été suppriméé !"
            );
        }
       

       
        return $this->redirectToRoute('admin_ads_index');
    }
}
