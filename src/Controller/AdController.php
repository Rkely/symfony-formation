<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     * @Route("/voir/{slug}", name="voir_annonce")
     * 
     * @return Response
     */
    public function voir($slug, AdRepository $rep)
    {
        $ad = $rep->findBySlug($slug);
        
        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);
        
    }
}
