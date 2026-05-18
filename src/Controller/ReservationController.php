<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;

use App\Repository\ReservationRepository;
use App\Repository\CarRepository;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\String\Slugger\SluggerInterface;

use Symfony\Component\HttpFoundation\File\Exception\FileException;

use Symfony\Component\Security\Http\Attribute\IsGranted;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/reservation')]
final class ReservationController extends AbstractController
{
    #[Route(name: 'app_reservation_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(
        ReservationRepository $reservationRepository
    ): Response {

        return $this->render('reservation/index.html.twig', [

            'reservations' => $reservationRepository->findBy([
                'status' => ['pending', 'approved']
            ]),
        ]);
    }

    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        ReservationRepository $reservationRepository,
        CarRepository $carRepository,
        SluggerInterface $slugger
    ): Response {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // EMAIL VERIFICATION CHECK
        if (!$this->getUser()->isVerified()) {

            $this->addFlash(
                'danger',
                'Please verify your email before reserving a car.'
            );

            return $this->redirectToRoute('home');
        }

        $reservation = new Reservation();

        // AUTO SELECT CAR
        $carId = $request->query->get('car');

        if ($carId) {

            $car = $carRepository->find($carId);

            if ($car) {

                $reservation->setCar($car);
            }
        }

        $form = $this->createForm(
            ReservationType::class,
            $reservation
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $reservation->setUser($this->getUser());

            $start = $reservation->getStartDate();
            $end   = $reservation->getEndDate();
            $car   = $reservation->getCar();

            if (!$start || !$end || !$car) {

                $this->addFlash(
                    'error',
                    'Please fill all fields correctly.'
                );

                return $this->redirectToRoute(
                    'app_reservation_new'
                );
            }

            if ($start > $end) {

                $this->addFlash(
                    'error',
                    'Start date must be before end date.'
                );

                return $this->redirectToRoute(
                    'app_reservation_new'
                );
            }

            $licenseDate = $reservation->getLicenseIssueDate();

            if ($licenseDate) {

                $today = new \DateTime();

                $difference = $today->diff($licenseDate);

                if ($difference->y < 2) {

                    $this->addFlash(
                        'error',
                        'Driver license must be older than 2 years.'
                    );

                    return $this->redirectToRoute(
                        'app_reservation_new'
                    );
                }
            }

            if (
                $reservationRepository->hasOverlap(
                    $car,
                    $start,
                    $end
                )
            ) {

                $this->addFlash(
                    'error',
                    'This car is already reserved for these dates.'
                );

                return $this->redirectToRoute(
                    'app_reservation_new'
                );
            }

            // CIN IMAGE
            $cinFile = $form->get('cinImage')->getData();

            if ($cinFile) {

                $originalFilename = pathinfo(
                    $cinFile->getClientOriginalName(),
                    PATHINFO_FILENAME
                );

                $safeFilename = $slugger->slug(
                    $originalFilename
                );

                $newFilename =
                    $safeFilename
                    .'-'
                    .uniqid()
                    .'.'
                    .$cinFile->guessExtension();

                try {

                    $cinFile->move(
                        $this->getParameter('kernel.project_dir')
                        .'/public/uploads/cin',
                        $newFilename
                    );

                } catch (FileException $e) {

                }

                $reservation->setCinImage($newFilename);
            }

            // LICENSE IMAGE
            $licenseFile = $form->get('licenseImage')->getData();

            if ($licenseFile) {

                $originalFilename = pathinfo(
                    $licenseFile->getClientOriginalName(),
                    PATHINFO_FILENAME
                );

                $safeFilename = $slugger->slug(
                    $originalFilename
                );

                $newFilename =
                    $safeFilename
                    .'-'
                    .uniqid()
                    .'.'
                    .$licenseFile->guessExtension();

                try {

                    $licenseFile->move(
                        $this->getParameter('kernel.project_dir')
                        .'/public/uploads/licenses',
                        $newFilename
                    );

                } catch (FileException $e) {

                }

                $reservation->setLicenseImage($newFilename);
            }

            // TOTAL PRICE
            $days = max(
                1,
                $start->diff($end)->days
            );

            $reservation->setTotalPrice(
                $days * $car->getPricePerDay()
            );

            // DEFAULT STATUS
            $reservation->setStatus('pending');

            // SAVE
            $entityManager->persist($reservation);

            $entityManager->flush();

            $this->addFlash(
                'success',
                'Reservation created successfully.'
            );

            return $this->redirectToRoute(
                'app_my_reservations'
            );
        }

        return $this->render(
            'reservation/new.html.twig',
            [
                'reservation' => $reservation,
                'form' => $form,
            ]
        );
    }

    #[Route('/my-reservations', name: 'app_my_reservations', methods: ['GET'])]
    public function myReservations(
        ReservationRepository $reservationRepository
    ): Response {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $reservations = $reservationRepository->findBy(
            ['user' => $this->getUser()],
            ['id' => 'DESC']
        );

        return $this->render(
            'reservation/my_reservations.html.twig',
            [
                'reservations' => $reservations,
            ]
        );
    }
    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function show(
        Reservation $reservation
    ): Response {

        return $this->render(
            'reservation/show.html.twig',
            [
                'reservation' => $reservation,
            ]
        );
    }

    #[Route('/{id}/approve', name: 'app_reservation_approve', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function approve(
        Reservation $reservation,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
    ): Response {

        $reservation->setStatus('approved');

        $reservation->getCar()->setStatus('unavailable');

        $entityManager->flush();

        $email = (new Email())
            ->from('noreply@carbook.com')
            ->to($reservation->getUser()->getEmail())
            ->subject('Reservation Approved')
            ->html('
                <h2>Your reservation has been approved ✅</h2>

                <p>
                    Your reservation for
                    <strong>'
                .$reservation->getCar()->getBrand().' '
                .$reservation->getCar()->getModel().
                '</strong>
                    has been approved.
                </p>
            ');

        $mailer->send($email);

        $this->addFlash(
            'success',
            'Reservation approved successfully.'
        );

        return $this->redirectToRoute(
            'app_reservation_index'
        );
    }

    #[Route('/{id}/reject', name: 'app_reservation_reject', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function reject(
        Reservation $reservation,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
    ): Response {

        $reservation->setStatus('rejected');

        $entityManager->flush();

        $email = (new Email())
            ->from('noreply@carbook.com')
            ->to($reservation->getUser()->getEmail())
            ->subject('Reservation Rejected')
            ->html('
                <h2>Your reservation has been rejected ❌</h2>
            ');

        $mailer->send($email);

        $this->addFlash(
            'danger',
            'Reservation rejected.'
        );

        return $this->redirectToRoute(
            'app_reservation_index'
        );
    }

    #[Route('/{id}/complete', name: 'app_reservation_complete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function complete(
        Reservation $reservation,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
    ): Response {

        $reservation->setStatus('completed');

        $reservation->getCar()->setStatus('available');

        $entityManager->flush();

        $email = (new Email())
            ->from('noreply@carbook.com')
            ->to($reservation->getUser()->getEmail())
            ->subject('Reservation Completed')
            ->html('
                <h2>Your reservation is completed 🚗</h2>
            ');

        $mailer->send($email);

        $this->addFlash(
            'success',
            'Reservation completed successfully.'
        );

        return $this->redirectToRoute(
            'app_reservation_index'
        );
    }

    #[Route('/{id}/invoice', name: 'app_reservation_invoice', methods: ['GET'])]
    public function invoice(
        Reservation $reservation
    ): Response {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (
            $reservation->getUser() !== $this->getUser()
            && !$this->isGranted('ROLE_ADMIN')
        ) {

            throw $this->createAccessDeniedException();
        }

        if (!in_array($reservation->getStatus(), ['approved', 'completed'])) {

            $this->addFlash(
                'danger',
                'Invoice only available for approved reservations.'
            );

            return $this->redirectToRoute('app_my_reservations');
        }

        $options = new Options();

        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);

        $html = $this->renderView(
            'reservation/invoice.html.twig',
            [
                'reservation' => $reservation,
            ]
        );

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        return new Response(

            $dompdf->stream(
                'reservation-'.$reservation->getId().'.pdf',
                [
                    'Attachment' => true
                ]
            ),

            200,

            [
                'Content-Type' => 'application/pdf'
            ]
        );
    }
}
