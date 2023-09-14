<?php

namespace App\Controller;

use App\Entity\Movie as MovieEntity;
use App\Form\MovieType;
use App\Model\Movie;
use App\Model\Security;
use App\Omdb\Client\ApiClientInterface;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    public function __construct(
        private readonly ApiClientInterface $omdbApiClient,
    ) {
    }

    #[Route(
        path: '/movies',
        name: 'app_movies_list',
        methods: ['GET']
    )]
    public function list(MovieRepository $movieRepository): Response
    {
        return $this->render('movie/list.html.twig', [
            'movies' => Movie::fromEntities($movieRepository->listAll()),
        ]);
    }

    #[Route(
        path: '/movies/{slug}',
        name: 'app_movies_details',
        requirements: [
            'slug' => MovieEntity::SLUG_FORMAT,
        ],
        methods: ['GET']
    )]
    public function details(MovieRepository $movieRepository, string $slug): Response
    {
        $movie = Movie::fromEntity($movieRepository->getBySlug($slug));

        $this->denyAccessUnlessGranted(Security::MOVIE_VIEW_DETAILS, $movie);

        return $this->render('movie/details.html.twig', [
            'movie' => $movie,
            'can_edit' => true,
        ]);
    }

    #[Route(
        path: '/movies/{imdbId}',
        name: 'app_movies_details_omdb',
        requirements: [
            'imdbId' => 'tt.+',
        ],
        methods: ['GET']
    )]
    public function detailsFromOmdb(string $imdbId): Response
    {
        $movie = Movie::fromOmdb($this->omdbApiClient->getById($imdbId));

        $this->denyAccessUnlessGranted(Security::MOVIE_VIEW_DETAILS, $movie);

        return $this->render('movie/details.html.twig', [
            'movie' => $movie,
            'can_edit' => false,
        ]);
    }

    #[Route(
        path: '/admin/movies/new',
        name: 'app_movies_new',
        methods: ['GET', 'POST']
    )]
    #[Route(
        path: '/admin/movies/{slug}/edit',
        name: 'app_movies_edit',
        requirements: [
            'slug' => MovieEntity::SLUG_FORMAT,
        ],
        methods: ['GET', 'POST']
    )]
    public function newOrEdit(
        Request $request,
        MovieRepository $movieRepository,
        EntityManagerInterface $entityManager,
        string|null $slug = null
    ): Response {
        $movieEntity = new MovieEntity();
        if (null !== $slug) {
            $movieEntity = $movieRepository->getBySlug($slug);
        }

        $movieForm = $this->createForm(MovieType::class, $movieEntity);
        $movieForm->handleRequest($request);

        if ($movieForm->isSubmitted() && $movieForm->isValid()) {
            $entityManager->persist($movieEntity);
            $entityManager->flush();

            return $this->redirectToRoute('app_movies_details', ['slug' => $movieEntity->getSlug()]);
        }

        return $this->render('movie/new_or_edit.html.twig', [
            'movie_form' => $movieForm,
            'editing_movie' => null !== $slug ? Movie::fromEntity($movieEntity) : null,
        ]);
    }
}
