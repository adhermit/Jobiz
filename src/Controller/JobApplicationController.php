<?php

namespace App\Controller;

use App\Entity\JobApplication;
use App\Form\JobApplicationType;
use App\Repository\JobApplicationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/job/application')]
final class JobApplicationController extends AbstractController
{
    #[Route(name: 'app_job_application_index', methods: ['GET'])]
    public function index(JobApplicationRepository $jobApplicationRepository): Response
    {
        return $this->render('job_application/index.html.twig', [
            'job_applications' => $jobApplicationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_job_application_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $jobApplication = new JobApplication();
        $form = $this->createForm(JobApplicationType::class, $jobApplication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($jobApplication);
            $entityManager->flush();

            return $this->redirectToRoute('app_job_application_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('job_application/new.html.twig', [
            'job_application' => $jobApplication,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_job_application_show', methods: ['GET'])]
    public function show(JobApplication $jobApplication): Response
    {
        return $this->render('job_application/show.html.twig', [
            'job_application' => $jobApplication,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_job_application_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, JobApplication $jobApplication, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(JobApplicationType::class, $jobApplication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_job_application_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('job_application/edit.html.twig', [
            'job_application' => $jobApplication,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_job_application_delete', methods: ['POST'])]
    public function delete(Request $request, JobApplication $jobApplication, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$jobApplication->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($jobApplication);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_job_application_index', [], Response::HTTP_SEE_OTHER);
    }
}
