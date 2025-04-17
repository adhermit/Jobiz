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

    
    #[Route('/job/search', name: 'app_job_search')]
    public function search(Request $request, JobRepository $jobRepository, CategoryRepository $categoryRepository): Response
    {
        $search = $request->query->get('search');
        $minSalary = $request->query->get('minimum_salary');
        $maxSalary = $request->query->get('maximum_salary');
        $country = $request->query->get('country');
        $city = $request->query->get('city');
        $categoryId = $request->query->get('category');
    
        // ✅ Convert to integers or null
        $minSalary = is_numeric($minSalary) ? (int)$minSalary : null;
        $maxSalary = is_numeric($maxSalary) ? (int)$maxSalary : null;
        $categoryId = is_numeric($categoryId) ? (int)$categoryId : null;
    
        $jobs = $jobRepository->findBySearch($search, $minSalary, $maxSalary, $country, $city, $categoryId);
    
        return $this->render('job/search.html.twig', [
            'jobs' => $jobs,
            'search' => $search,
            'minSalary' => $minSalary,
            'maxSalary' => $maxSalary,
            'country' => $country,
            'city' => $city,
            'categoryId' => $categoryId,
            'categories' => $categoryRepository->findAll(),
        ]);
    }
    
    #[Route('/job/{id}', name: 'app_job_show')]
public function show(Request $request, Job $job = null, JobRepository $jobRepository, EntityManagerInterface $em, CategoryRepository $categoryRepository, Security $security): Response
{
    if (!$job) {
        throw $this->createNotFoundException('Job not found');
    }

    // Get query parameters for search filters
    $search = $request->query->get('search');
    $minimumSalary = $request->query->get('minimum_salary');
    $maximumSalary = $request->query->get('maximum_salary');
    $country = $request->query->get('country');
    $city = $request->query->get('city');
    $selectedCategory = $request->query->get('category');

    // Convert to integers for salary and category if they are valid numbers
    $minSalary = is_numeric($minimumSalary) ? (int)$minimumSalary : null;
    $maxSalary = is_numeric($maximumSalary) ? (int)$maximumSalary : null;
    $selectedCategory = is_numeric($selectedCategory) ? (int)$selectedCategory : null;

    // Filter jobs based on search parameters
    $filteredJobs = $jobRepository->findBySearch($search, $minSalary, $maxSalary, $country, $city, $selectedCategory);

    // Convert job description to HTML (using markdown)
    $converter = new CommonMarkConverter();
    $descriptionHtml = $converter->convert($job->getDescription())->getContent();

    // Fetch all categories
    $category = $categoryRepository->findAll();

    // Create a new application instance
    $application = new JobApplication();
    $application->setCreatedAt(new \DateTimeImmutable());
    $application->setJob($job);

    // Get current logged-in user
    $user = $security->getUser();

    if ($user instanceof \App\Entity\User) {
       //$application->setFullName($user->getFullName());
        //$application->setName($user->getFullName());
    }

    // Create the application form
    $form = $this->createForm(ApplyFormType::class, $application);
    $form->handleRequest($request);

    // Check if the form is submitted and valid
    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($application);
        $em->flush();
        // Redirect with "applied" query parameter to show success message
        return $this->redirectToRoute('app_job_show', ['id' => $job->getId(), 'applied' => 1]);
    }

    // Check if the form was submitted successfully
    $formSubmitted = $request->query->get('applied') == 1;

    // Return the response with the job details, application form, and any other necessary data
    return $this->render('job/show.html.twig', [
        'job' => $job,
        'descriptionHtml' => $descriptionHtml,
        'categories' => $category,
        'filteredJobs' => $filteredJobs,
        'search' => $search,
        'applyForm' => $form->createView(),
        'formSubmitted' => $formSubmitted,
        'user' => $user, // Pass user to the template to check for login status
    ]);
}
    
}