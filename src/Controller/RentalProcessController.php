<?php

namespace App\Controller;

use App\Entity\RentalProcess;
use App\Form\RentalProcessType;
use App\Repository\RentalProcessRepository;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\ReturnProcessType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/rental/process')]
final class RentalProcessController extends AbstractController
{
    #[Route(name: 'app_rental_process_index', methods: ['GET'])]
    public function index(
        RentalProcessRepository $rentalProcessRepository
    ): Response {

        return $this->render(
            'rental_process/index.html.twig',
            [
                'rental_processes' =>
                    $rentalProcessRepository->findAll(),
            ]
        );
    }

    #[Route('/new', name: 'app_rental_process_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {

        $rentalProcess = new RentalProcess();

        $form = $this->createForm(
            RentalProcessType::class,
            $rentalProcess
        );

        $form->handleRequest($request);

        if (
            $form->isSubmitted()
            && $form->isValid()
        ) {

            $entityManager->persist($rentalProcess);

            $entityManager->flush();

            return $this->redirectToRoute(
                'app_rental_process_index'
            );
        }

        return $this->render(
            'rental_process/new.html.twig',
            [
                'rental_process' => $rentalProcess,
                'form' => $form,
            ]
        );
    }

    #[Route('/{id}/edit', name: 'app_rental_process_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        RentalProcess $rentalProcess,
        EntityManagerInterface $entityManager
    ): Response {

        $form = $this->createForm(
            RentalProcessType::class,
            $rentalProcess
        );

        $form->handleRequest($request);

        if (
            $form->isSubmitted()
        ) {

            // PICKUP CONFIRMED
            $rentalProcess->setStatus(
                'picked_up'
            );

            $entityManager->flush();

            $this->addFlash(
                'success',
                'Vehicle pickup confirmed successfully.'
            );

            return $this->redirectToRoute(
                'app_reservation_show',
                [
                    'id' => $rentalProcess
                        ->getReservation()
                        ->getId()
                ]
            );
        }

        return $this->render(
            'rental_process/edit.html.twig',
            [
                'rental_process' => $rentalProcess,
                'form' => $form,
            ]
        );
    }

    #[Route('/{id}/return/edit', name: 'app_rental_process_return_edit', methods: ['GET', 'POST'])]
    public function returnEdit(
        Request $request,
        RentalProcess $rentalProcess,
        EntityManagerInterface $entityManager
    ): Response {

        $form = $this->createForm(
            ReturnProcessType::class,
            $rentalProcess
        );

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            if (
                (int) $rentalProcess->getReturnKilometers()
                <=
                (int) $rentalProcess->getPickupKilometers()
            ) {

                $this->addFlash(
                    'danger',
                    'Return kilometers must be greater than pickup kilometers.'
                );

                return $this->render(
                    'rental_process/return_edit.html.twig',
                    [
                        'rental_process' => $rentalProcess,
                        'form' => $form,
                    ]
                );
            }

            // COMPLETE RENTAL
            $rentalProcess->setStatus(
                'completed'
            );

            // COMPLETE RESERVATION
            $rentalProcess
                ->getReservation()
                ->setStatus('completed');

            $entityManager->flush();

            $this->addFlash(
                'success',
                'Vehicle returned successfully.'
            );

            return $this->redirectToRoute(
                'app_reservation_show',
                [
                    'id' => $rentalProcess
                        ->getReservation()
                        ->getId()
                ]
            );
        }

        return $this->render(
            'rental_process/return_edit.html.twig',
            [
                'rental_process' => $rentalProcess,
                'form' => $form,
            ]
        );
    }



    #[Route('/{id}', name: 'app_rental_process_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        RentalProcess $rentalProcess,
        EntityManagerInterface $entityManager
    ): Response {

        if (
            $this->isCsrfTokenValid(
                'delete'.$rentalProcess->getId(),
                $request->getPayload()->getString('_token')
            )
        ) {

            $entityManager->remove($rentalProcess);

            $entityManager->flush();
        }

        return $this->redirectToRoute(
            'app_rental_process_index'
        );
    }
}
