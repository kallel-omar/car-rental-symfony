<?php

namespace App\Controller;

use App\Entity\User;

use App\Repository\UserRepository;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Attribute\Route;


use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Form\EditProfileType;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\String\Slugger\SluggerInterface;

use Symfony\Component\HttpFoundation\File\Exception\FileException;



final class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(
        UserRepository $userRepository,
        Request $request
    ): Response {

        $type = $request->query->get('type');
        $search = $request->query->get('search');

        $qb = $userRepository->createQueryBuilder('u');

        if ($type === 'clients') {
            $qb->where('u.roles NOT LIKE :admin')
                ->andWhere('u.roles NOT LIKE :superviser')
                ->setParameter('admin', '%ROLE_ADMIN%')
                ->setParameter('superviser', '%ROLE_SUPERVISER%');
        }

        if ($type === 'staff') {
            $qb->where('u.roles LIKE :admin OR u.roles LIKE :superviser')
                ->setParameter('admin', '%ROLE_ADMIN%')
                ->setParameter('superviser', '%ROLE_SUPERVISER%');
        }

        if ($search) {
            $qb->andWhere(
                'u.fullName LIKE :search
             OR u.email LIKE :search
             OR u.phoneNumber LIKE :search'
            )
                ->setParameter('search', '%' . $search . '%');
        }

        $users = $qb->getQuery()->getResult();

        return $this->render('user/index.html.twig', [
            'users' => $users,
            'currentType' => $type,
            'search' => $search,
        ]);
    }


    #[Route('/{id}/make-admin', name: 'app_user_make_admin', methods: ['POST'])]
    #[IsGranted('ROLE_SUPERVISER')]
    public function makeAdmin(
        User $user,
        EntityManagerInterface $entityManager
    ): Response {

        $roles = $user->getRoles();

        if (
            !in_array('ROLE_ADMIN', $roles)
            && !in_array('ROLE_SUPERVISER', $roles)
        ) {

            $roles[] = 'ROLE_ADMIN';

            $user->setRoles($roles);

            $entityManager->flush();

            $this->addFlash(
                'success',
                'User is now admin.'
            );
        }

        return $this->redirectToRoute(
            'app_user_index'
        );
    }


    #[Route('/{id}/remove-admin', name: 'app_user_remove_admin', methods: ['POST'])]
    #[IsGranted('ROLE_SUPERVISER')]
    public function removeAdmin(
        User $user,
        EntityManagerInterface $entityManager
    ): Response {

        $roles = $user->getRoles();

        $roles = array_filter(
            $roles,
            fn($role) => $role !== 'ROLE_ADMIN'
        );

        if (empty($roles)) {

            $roles = ['ROLE_USER'];
        }

        $user->setRoles($roles);

        $entityManager->flush();

        $this->addFlash(
            'success',
            'Admin role removed.'
        );

        return $this->redirectToRoute(
            'app_user_index'
        );
    }


    #[Route('/{id}/make-superviser', name: 'app_user_make_superviser', methods: ['POST'])]
    #[IsGranted('ROLE_SUPERVISER')]
    public function makeSuperviser(
        User $user,
        EntityManagerInterface $entityManager
    ): Response {

        if (!in_array('ROLE_SUPERVISER', $user->getRoles())) {

            $user->setRoles(['ROLE_SUPERVISER']);

            $entityManager->flush();

            $this->addFlash(
                'success',
                'User is now superviser.'
            );
        }

        return $this->redirectToRoute(
            'app_user_index'
        );
    }


    #[Route('/{id}/remove-superviser', name: 'app_user_remove_superviser', methods: ['POST'])]
    #[IsGranted('ROLE_SUPERVISER')]
    public function removeSuperviser(
        User $user,
        EntityManagerInterface $entityManager
    ): Response {

        $user->setRoles(['ROLE_USER']);

        $entityManager->flush();

        $this->addFlash(
            'success',
            'Superviser role removed.'
        );

        return $this->redirectToRoute(
            'app_user_index'
        );
    }
    #[Route('/profile', name: 'app_user_profile')]
    public function profile(): Response
    {
        $user = $this->getUser();

        $latestReservation = null;

        $pendingPaymentReservation = null;

        foreach ($user->getReservations() as $reservation) {

            // approved + paid
            if (
                !$latestReservation
                && $reservation->getStatus() === 'approved'
                && $reservation->getPaymentStatus() === 'paid'
            ) {

                $latestReservation = $reservation;
            }

            // approved + unpaid
            if (
                !$pendingPaymentReservation
                && $reservation->getStatus() === 'approved'
                && $reservation->getPaymentStatus() === 'unpaid'
            ) {

                $pendingPaymentReservation = $reservation;
            }
        }

        return $this->render('user/profile.html.twig', [

            'userData' => $user,

            'latestReservation' => $latestReservation,

            'pendingPaymentReservation' => $pendingPaymentReservation,
        ]);
    }
    #[Route('/profile/edit', name: 'app_user_edit_profile')]
    public function editProfile(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {

        $this->denyAccessUnlessGranted(
            'IS_AUTHENTICATED_FULLY'
        );

        $user = $this->getUser();

        $form = $this->createForm(
            EditProfileType::class,
            $user
        );

        $form->handleRequest($request);

        if (
            $form->isSubmitted()
            && $form->isValid()
        ) {

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

                $user->setCinImage($newFilename);
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

                $user->setLicenseImage($newFilename);
            }

            $entityManager->flush();

            $this->addFlash(
                'success',
                'Profile updated successfully.'
            );

            return $this->redirectToRoute(
                'app_user_profile'
            );
        }

        return $this->render(
            'user/edit_profile.html.twig',
            [
                'form' => $form,
            ]
        );
    }
    #[Route('/admin/user/{id}', name: 'app_admin_user_profile')]
    #[IsGranted('ROLE_ADMIN')]
    public function adminUserProfile(User $user): Response
    {
        return $this->render('admin/user_profile.html.twig', [

            'userData' => $user,
        ]);
    }
}
