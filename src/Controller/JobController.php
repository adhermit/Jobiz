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

        $page = $request->query->getInt('page', 1);
        $limit = 10;

        $jobs = $jobRepository->findBy([], null, $limit, ($page - 1) * $limit);
        $totalJobs = $jobRepository->count([]);
        $totalPages = ceil($totalJobs / $limit);
        $category = $categoryRepository->findAll();

        return $this->render('job/list.html.twig', [
            'jobs' => $jobs,
            'color' => 'white',
            'categories' => $category,
            'page' => $page,
            'limit' => $limit,
            'totalPages' => $totalPages,
        ]);
    }

    #[Route('/job/{id}', name: 'app_job_show')]
    public function show(Request $request, Job $job, JobRepository $jobRepository, CategoryRepository $categoryRepository): Response
    {
        $search = $request->query->get('search');
        $minimumSalary = $request->query->get('minimum_salary');
        $maximumSalary = $request->query->get('maximum_salary');
        $country = $request->query->get('country');
        $city = $request->query->get('city');
        $selectedCategory = $request->query->get('category');

        $filteredJobs = $jobRepository->findBySearch($search, $minimumSalary, $maximumSalary, $country, $city, $selectedCategory);

        $converter = new CommonMarkConverter();
        $descriptionHtml = $converter->convert($job->getDescription())->getContent();

        $category = $categoryRepository->findAll();

        return $this->render('job/show.html.twig', [
            'job' => $job,
            'descriptionHtml' => $descriptionHtml,
            'categories' => $category,
            'filteredJobs' => $filteredJobs,
            'search' => $search,
        ]);
    }

    #[Route('/job/search', name: 'app_job_search')]
    public function search(Request $request, JobRepository $jobRepository, CategoryRepository $categoryRepository): Response
    {
    $search = $request->query->get('search');
    $minSalary = $request->query->get('minimum_salary');
    $maxSalary = $request->query->get('maximum_salary');
    $country = $request->query->get('country');
    $city = $request->query->get('city');
    $categoryId = $request->query->get('category');

    $qb = $jobRepository->createQueryBuilder('j');

    if ($search) {
        $qb->andWhere('j.title LIKE :search OR j.description LIKE :search')
           ->setParameter('search', '%' . $search . '%');
    }

    if ($minSalary) {
        $qb->andWhere('j.minSalary >= :minSalary')
           ->setParameter('minSalary', $minSalary);
    }

    if ($maxSalary) {
        $qb->andWhere('j.maxSalary <= :maxSalary')
           ->setParameter('maxSalary', $maxSalary);
    }

    if ($country) {
        $qb->andWhere('j.country = :country')
           ->setParameter('country', $country);
    }

    if ($city) {
        $qb->andWhere('j.city = :city')
           ->setParameter('city', $city);
    }

    if ($categoryId) {
        $qb->join('j.categories', 'c')
           ->andWhere('c.id = :categoryId')
           ->setParameter('categoryId', $categoryId);
    }

    return $qb->getQuery()->getResult();
    }
}