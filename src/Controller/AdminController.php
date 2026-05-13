<?php

namespace App\Controller;

use App\Repository\CarRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/dashboard', name: 'admin_dashboard')]
    public function index(
        CarRepository $carRepository,
        ReservationRepository $reservationRepository
    ): Response {

        // FORCE LOGIN CHECK
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $carsCount = $carRepository->count([]);
        $reservationsCount = $reservationRepository->count([]);

        $reservations = $reservationRepository->findBy([], ['id' => 'DESC'], 5);

        $totalRevenue = 0;
        foreach ($reservationRepository->findAll() as $r) {
            $totalRevenue += $r->getTotalPrice();
        }

        return $this->render('admin/dashboard.html.twig', [
            'carsCount' => $carsCount,
            'reservationsCount' => $reservationsCount,
            'reservations' => $reservations,
            'totalRevenue' => $totalRevenue,
        ]);
    }
}
