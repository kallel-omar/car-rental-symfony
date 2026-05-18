<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\CarType;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\Security\Http\Attribute\IsGranted;

use Symfony\Component\String\Slugger\SluggerInterface;

use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/car')]
final class CarController extends AbstractController
{
    #[Route(name: 'app_car_index', methods: ['GET'])]
    public function index(
        Request $request,
        CarRepository $carRepository
    ): Response {

        $search = $request->query->get('search');

        $transmission = $request->query->get('transmission');

        $fuelType = $request->query->get('fuelType');

        $available = $request->query->get('available');

        $qb = $carRepository->createQueryBuilder('c');

        // SEARCH
        if ($search) {

            $qb->andWhere('c.brand LIKE :search OR c.model LIKE :search')
                ->setParameter('search', '%'.$search.'%');
        }

        // TRANSMISSION
        if ($transmission) {

            $qb->andWhere('c.transmission = :transmission')
                ->setParameter('transmission', $transmission);
        }

        // FUEL TYPE
        if ($fuelType) {

            $qb->andWhere('c.fuelType = :fuelType')
                ->setParameter('fuelType', $fuelType);
        }

        // AVAILABLE ONLY
        if ($available) {

            $qb->andWhere('c.status = :status')
                ->setParameter('status', 'available');
        }

        $cars = $qb->getQuery()->getResult();

        return $this->render('car/index.html.twig', [
            'cars' => $cars,
        ]);
    }

    #[Route('/new', name: 'app_car_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {

        $car = new Car();

        $form = $this->createForm(
            CarType::class,
            $car
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // IMAGE UPLOAD
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {

                $originalFilename = pathinfo(
                    $imageFile->getClientOriginalName(),
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
                    .$imageFile->guessExtension();

                try {

                    $imageFile->move(
                        $this->getParameter('kernel.project_dir')
                        .'/public/uploads/cars',
                        $newFilename
                    );

                } catch (FileException $e) {

                }

                $car->setImage($newFilename);
            }

            // DEFAULT STATUS
            if (!$car->getStatus()) {

                $car->setStatus('available');
            }

            $entityManager->persist($car);

            $entityManager->flush();

            $this->addFlash(
                'success',
                'Car created successfully.'
            );

            return $this->redirectToRoute(
                'app_car_index'
            );
        }

        return $this->render('car/new.html.twig', [
            'car' => $car,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_car_show', methods: ['GET'])]
    public function show(
        Car $car
    ): Response {

        return $this->render('car/show.html.twig', [
            'car' => $car,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_car_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(
        Request $request,
        Car $car,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {

        $form = $this->createForm(
            CarType::class,
            $car
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // IMAGE UPLOAD
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {

                $originalFilename = pathinfo(
                    $imageFile->getClientOriginalName(),
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
                    .$imageFile->guessExtension();

                try {

                    $imageFile->move(
                        $this->getParameter('kernel.project_dir')
                        .'/public/uploads/cars',
                        $newFilename
                    );

                } catch (FileException $e) {

                }

                $car->setImage($newFilename);
            }

            $entityManager->flush();

            $this->addFlash(
                'success',
                'Car updated successfully.'
            );

            return $this->redirectToRoute(
                'app_car_index'
            );
        }

        return $this->render('car/edit.html.twig', [
            'car' => $car,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_car_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(
        Request $request,
        Car $car,
        EntityManagerInterface $entityManager
    ): Response {

        if (
            $this->isCsrfTokenValid(
                'delete'.$car->getId(),
                $request->getPayload()->getString('_token')
            )
        ) {

            $entityManager->remove($car);

            $entityManager->flush();

            $this->addFlash(
                'success',
                'Car deleted successfully.'
            );
        }

        return $this->redirect(
            $request->headers->get('referer')
        );
    }
}
