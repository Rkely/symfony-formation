<?php

namespace App\Controller;

use App\Service\StatsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminDashboardController extends Controller
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(EntityManagerInterface $manager, StatsService $statsService)
    {
        $stats      = $statsService->getStats();
        $bestAds    = $statsService->getAdsStats('DESC');
        $worstAds   = $statsService->getAdsStats('ASC');
        return $this->render('admin/dashboard/index.html.twig', [
            'stats'     => $stats,
            'bestAds'   => $bestAds,
            'worstAds'  => $worstAds
        ]);
    }
}