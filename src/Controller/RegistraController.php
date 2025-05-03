<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class RegistraController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['GET'])]
    public function chooseRegistrationType(): Response
    {
        return $this->render('register/choose.html.twig');
    }

    #[Route('/register/job-seeker', name: 'app_register_job_seeker', methods: ['GET', 'POST'])]
    public function registerJobSeeker(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        
        $user = new User();
        $user->setRoles(['ROLE_JOB_SEEKER']);

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword($user, $form->get('plainPassword')->getData());
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Registration successful! You can now log in.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('register/seeker.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/register/recruiter', name: 'app_register_recruiter', methods: ['GET', 'POST'])]
    public function registerRecruiter(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        
        $user = new User();
        $user->setRoles(['ROLE_RECRUITER']);

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword($user, $form->get('plainPassword')->getData());
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Registration successful! You can now log in.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('register/recruiter.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}