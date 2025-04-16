<?php

namespace App\Controller;

use App\Entity\Job;
use App\Entity\User;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\JobRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use League\CommonMark\CommonMarkConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Compinent\Security\Core\Exception\AccessDeniedException;

final class JobController extends AbstractController
{
    #[Route('/job/list', name: 'app_job_list')]
    public function index(Request $request, JobRepository $jobRepository, CategoryRepository $categoryRepository): Response
    {
        $search = $request->request->get('search');
        $minimumSalary = $request->request->get('minimum_salary');
        $maximumSalary = $request->request->get('maximum_salary');
        $country = $request->request->get('country');
        $city = $request->request->get('city');
        $category = $request->request->get('category');
        $jobs = $jobRepository->findBySearch($search, $minimumSalary, $maximumSalary, $country, $city);

        $category = $categoryRepository->findAll();

        $jobs = $jobRepository->findAll();
        $jobs = $jobRepository->findBySearch($search, $minimumSalary, $maximumSalary, $country, $city); 

        return $this->render('job/list.html.twig', [
            'jobs' => $jobs,
            'color' => 'white',
            'categories' => $category,
        ]);
    }

    #[Route('/job/{id}', name: 'app_job_show')]
    public function show(Job $job, CategoryRepository $categoryRepository): Response
    {
        $converter = new CommonMarkConverter();
        $descriptionHtml = $converter->convert($job->getDescription())->getContent();

        $category = $categoryRepository->findAll();

        return $this->render('job/show.html.twig', [
            'job' => $job,
            'descriptionHtml' => $descriptionHtml,
            'categories' => $category,
        ]);
    }

    #[Route('/job/search', name: 'app_job_search')]
    public function search(Request $request, JobRepository $jobRepository): Response
    {
        $search = $request->query->get('search');

        $jobs = $jobRepository->findBySearch($search);

        return $this->render('job/search.html.twig', [
            'jobs' => $jobs,
            'color' => 'white',
            'search' => $search
        ]);
    }

}
