<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class NavbarController extends AbstractController
{
    public function main(MovieRepository $movieRepository): Response
    {
        return $this->render('navbar.html.twig', [
            'movies' => $movieRepository->listBySlugAndTitle(),
        ]);
    }
}
