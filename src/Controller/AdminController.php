<?php

namespace App\Controller;

use App\Repository\CarRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;


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
            ->where('r.status = :status')
            ->setParameter('status', 'completed')
            ->getQuery()
            ->getSingleScalarResult() ?? 0;

        return $this->render('admin/dashboard.html.twig', [
            'carsCount' => $carsCount,
            'reservationsCount' => $reservationsCount,
            'reservations' => $reservations,
            'totalRevenue' => $totalRevenue,
        ]);
    }


    // CARS MANAGEMENT PAGE

    #[Route('/cars', name: 'admin_cars')]
    public function cars(CarRepository $carRepository, Request $request): Response
    {
        $status = $request->query->get('status');

        if ($status) {
            $cars = $carRepository->findBy(['status' => $status]);
        } else {
            $cars = $carRepository->findAll();
        }

        return $this->render('admin/cars.html.twig', [
            'cars' => $cars,
            'currentStatus' => $status,
        ]);
    }
    #[Route('/admin/user/{id}/block', name: 'app_admin_block_user')]
    public function blockUser(
        User $user,
        EntityManagerInterface $entityManager
    ): Response {

        $user->setIsBlocked(true);

        $entityManager->flush();

        $this->addFlash('success', 'User blocked successfully.');

        return $this->redirectToRoute(
            'app_admin_user_profile',
            ['id' => $user->getId()]
        );
    }

    #[Route('/admin/user/{id}/unblock', name: 'app_admin_unblock_user')]
    public function unblockUser(
        User $user,
        EntityManagerInterface $entityManager
    ): Response {

        $user->setIsBlocked(false);

        $entityManager->flush();

        $this->addFlash('success', 'User unblocked successfully.');

        return $this->redirectToRoute(
            'app_admin_user_profile',
            ['id' => $user->getId()]
        );
    }
}
