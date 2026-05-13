<?php

namespace App\Controller;

use App\Repository\CarRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/dashboard', name: 'admin_dashboard')]
    public function index(
        CarRepository $carRepository,
        ReservationRepository $reservationRepository
    ): Response {

        $carsCount = $carRepository->count([]);
        $reservationsCount = $reservationRepository->count([]);

        $reservations = $reservationRepository->findBy([], ['id' => 'DESC'], 5);

        $totalRevenue = $reservationRepository->createQueryBuilder('r')
            ->select('SUM(r.totalPrice)')
            ->getQuery()
            ->getSingleScalarResult() ?? 0;

        return $this->render('admin/dashboard.html.twig', [
            'carsCount' => $carsCount,
            'reservationsCount' => $reservationsCount,
            'reservations' => $reservations,
            'totalRevenue' => $totalRevenue,
        ]);
    }
}
