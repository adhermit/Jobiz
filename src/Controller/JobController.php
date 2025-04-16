<?php

namespace App\Controller;

use App\Entity\Job;
use App\Repository\JobRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use League\CommonMark\CommonMarkConverter;

final class JobController extends AbstractController
{
    #[Route('/job/list', name: 'app_job_list')]
    public function index(JobRepository $jobRepository): Response
    {

        $jobs = $jobRepository->findAll();

        return $this->render('job/list.html.twig', [
            'jobs' => $jobs,
            'color' => 'white'
        ]);
    }

    #[Route('/job/{id}', name: 'app_job_show')]
    public function show(Job $job): Response
    {
        $converter = new CommonMarkConverter();
        $descriptionHtml = $converter->convert($job->getDescription())->getContent();


        return $this->render('job/show.html.twig', [
            'job' => $job,
            'descriptionHtml' => $descriptionHtml,
        ]);
    }
}
