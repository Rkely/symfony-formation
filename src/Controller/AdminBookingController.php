<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\BookingType;
use App\Form\AdminBookingType;
use App\Service\PaginationService;
use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/bookings/{page<\d+>?1}", name="admin_booking_index")
     */
    public function index(BookingRepository $rep, $page, PaginationService $pagination)
    {
        $pagination->setEntityClass(Booking::class)
        ->setPage($page)
        ->setLimit(20);
        return $this->render('admin/booking/index.html.twig', [
            'pagination' => $pagination
        ]);
    }
    /**
     * @Route("/admin/booking/{id}/edit", name="admin_booking_edit")
     */
    public function edit(Booking $booking, ObjectManager $manager, Request $request)
    {
        $form = $this->createForm(AdminBookingType::class, $booking);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $booking->setAmount($booking->getAd()->getPrice() * $booking->getDuration());
            // ou $booking->setAmount(0); avec la methode preUpdate va la gerer ou ceux dans haut
            $manager->persist($booking);
            $manager->flush();
            $this->addFlash(
                'success',
                "Modification effectuée"
            );
            return $this->redirectToRoute('admin_booking_index');
        }
        return $this->render('admin/booking/edit.html.twig', [
            'bookings' => $booking,
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/admin/booking/{id}/delete", name="admin_booking_delete")
     */
    public function delele(Booking $booking, ObjectManager $manager)
    {
            $manager->remove($booking);
            $manager->flush();
            $this->addFlash(
                'success',
                "Suppression effectuée"
            );
            return $this->redirectToRoute('admin_booking_index');
       
    }
}
