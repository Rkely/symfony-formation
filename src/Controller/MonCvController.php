<?php
namespace App\Controller;
use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
class HomeController extends Controller {
    /**
     * @Route("/telly-issa-cv", name="moncv")
     */
    public function home(){
        return $this->render('moncv/moncv.html');
    }
}
?>