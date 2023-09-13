<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MovieControllerTest extends WebTestCase
{
    /**
     * @group smoke-test
     */
    public function testCanListMovies(): void
    {
        $client = static::createClient();
        $client->request('GET', '/movies');

        $this->assertResponseIsSuccessful();
    }

    /**
     * @group application-test
     */
    public function testCanSeeEachMovieDetails(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/movies');

        $this->assertResponseIsSuccessful();

        $movies = $crawler->filter('div.card .card-body > a')->links();

        $this->assertCount(4, $movies);

        foreach ($movies as $movieLink) {
            $client->click($movieLink);
            $this->assertResponseIsSuccessful();
        }
    }
}
