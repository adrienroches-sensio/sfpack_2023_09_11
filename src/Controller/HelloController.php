<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    #[Route(
        path: '/hello/{name}',
        name: 'app_hello',
        requirements: [
            'name' => '\w([-\w])*'
        ],
        methods: ['GET'],
    )]
    public function index(string $name = 'Adrien'): Response
    {
        return new Response(<<<"HTML"
        <body>
            <span>Hello {$name} !</span>
        </body>
        HTML);
    }
}
