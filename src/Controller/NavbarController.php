<?php

namespace App\Controller;

use App\Model\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class NavbarController extends AbstractController
{
    public function main(): Response
    {
        return $this->render('navbar.html.twig', [
            'movies' => MovieRepository::listAll(),
        ]);
    }
}
