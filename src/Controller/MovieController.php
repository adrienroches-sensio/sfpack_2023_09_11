<?php

namespace App\Controller;

use App\Model\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    #[Route(
        path: '/movies',
        name: 'app_movies_list',
        methods: ['GET']
    )]
    public function list(): Response
    {
        return $this->render('movie/list.html.twig', [
            'movies' => MovieRepository::listAll(),
        ]);
    }
}
