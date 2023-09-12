<?php

namespace App\Controller;

use App\Entity\Movie as MovieEntity;
use App\Form\MovieType;
use App\Model\Movie;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class MovieController extends AbstractController
{
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
            'slug' => '\d{4}-'.Requirement::ASCII_SLUG,
        ],
        methods: ['GET']
    )]
    public function details(MovieRepository $movieRepository, string $slug): Response
    {
        return $this->render('movie/details.html.twig', [
            'movie' => Movie::fromEntity($movieRepository->getBySlug($slug)),
        ]);
    }

    #[Route(
        path: '/movies/new',
        name: 'app_movies_new',
        methods: ['GET']
    )]
    #[Route(
        path: '/movies/{slug}/edit',
        name: 'app_movies_edit',
        requirements: [
            'slug' => '\d{4}-'.Requirement::ASCII_SLUG,
        ],
        methods: ['GET']
    )]
    public function newOrEdit(MovieRepository $movieRepository, string|null $slug = null): Response
    {
        $movieEntity = new MovieEntity();
        if (null !== $slug) {
            $movieEntity = $movieRepository->getBySlug($slug);
        }

        $movieForm = $this->createForm(MovieType::class, $movieEntity);

        return $this->render('movie/new_or_edit.html.twig', [
            'movie_form' => $movieForm,
            'editing_movie' => null !== $slug ? Movie::fromEntity($movieEntity) : null,
        ]);
    }
}
