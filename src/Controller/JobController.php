<?php

namespace App\Controller;

use App\Entity\JobApplication;
use App\Entity\Job;
use App\Form\ApplyFormType;
use App\Entity\User;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\JobRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use League\CommonMark\CommonMarkConverter;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;


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
    public function show(Request $request, Job $job, JobRepository $jobRepository, EntityManagerInterface $em,CategoryRepository $categoryRepository): Response
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

        $application = new JobApplication();
        $application->setCreatedAt(new \DateTimeImmutable());
        $application->setJob($job);
    
        $user = $this->getUser();

        if ($user instanceof \App\Entity\User) {
            $application->setFullName($user->getFullName());
            $application->setName($user->getFullName());
        }
        // Create and handle the form for applying
        $form = $this->createForm(ApplyFormType::class, $application);
        $form->handleRequest($request);

        $formSubmitted = false;
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($application);
            $em->flush();
        
            
        
            // Redirect with query param to indicate success
            return $this->redirectToRoute('app_job_show', ['id' => $job->getId(), 'applied' => 1]);
        }
        $formSubmitted = $request->query->get('applied') == 1;


        return $this->render('job/show.html.twig', [
            'job' => $job,
            'descriptionHtml' => $descriptionHtml,
            'categories' => $category,
            'filteredJobs' => $filteredJobs,
            'search' => $search,
            'applyForm' => $form->createView(),
            'formSubmitted' => $formSubmitted,
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

    $jobs = $qb->getQuery()->getResult();

    return $this->render('job/search.html.twig', [
        'jobs' => $jobs,
        'search' => $search,
        'minSalary' => $minSalary,
        'maxSalary' => $maxSalary,
        'country' => $country,
        'city' => $city,
        'categoryId' => $categoryId,
        'categories' => $categoryRepository->findAll()
    ]);
    }
}