<?php
namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MonCvController extends AbstractController {
    /**
     * @Route("/telly-issa-cv", name="moncv")
     */
    public function home(){
        return $this->render('moncv/moncv.html.twig');
    }
}
?>