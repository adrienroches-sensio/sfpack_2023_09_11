<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use App\Entity\Movie;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * @phpstan-type MovieRaw array{
 *     slug: string,
 *     title: string,
 *     plot: string,
 *     poster: string,
 *     releasedAt: string,
 *     genres: list<string>
 * }
 */
class MovieFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var list<MovieRaw>
     */
    private const MOVIES = [
        [
            'slug' => '2002-asterix-et-obelix-mission-cleopatre',
            'title' => 'Astérix et Obélix: Mission Cléopâtre',
            'plot' => 'Cléopâtre, la reine d’Égypte, décide, pour défier l\'Empereur romain Jules César, de construire en trois mois un palais somptueux en plein désert. Si elle y parvient, celui-ci devra concéder publiquement que le peuple égyptien est le plus grand de tous les peuples. Pour ce faire, Cléopâtre fait appel à Numérobis, un architecte d\'avant-garde plein d\'énergie. S\'il réussit, elle le couvrira d\'or. S\'il échoue, elle le jettera aux crocodiles. Celui-ci, conscient du défi à relever, cherche de l\'aide auprès de son vieil ami Panoramix. Le druide fait le voyage en Égypte avec Astérix et Obélix. De son côté, Amonbofis, l\'architecte officiel de Cléopâtre, jaloux que la reine ait choisi Numérobis pour construire le palais, va tout mettre en œuvre pour faire échouer son concurrent.',
            'poster' => '2002-asterix-et-obelix-mission-cleopatre.png',
            'releasedAt' => '30 Jan 2002',
            'genres' => ['Comedy'],
        ],
        [
            'slug' => '2017-le-sens-de-la-fete',
            'title' => 'Le sens de la fête',
            'plot' => 'Max est traiteur depuis trente ans. Des fêtes il en a organisé des centaines, il est même un peu au bout du parcours. Aujourd\'hui c\'est un sublime mariage dans un château du 17ème siècle, un de plus, celui de Pierre et Héléna. Comme d\'habitude, Max a tout coordonné : il a recruté sa brigade de serveurs, de cuisiniers, de plongeurs, il a conseillé un photographe, réservé l\'orchestre, arrangé la décoration florale, bref tous les ingrédients sont réunis pour que cette fête soit réussie... Mais la loi des séries va venir bouleverser un planning sur le fil où chaque moment de bonheur et d\'émotion risque de se transformer en désastre ou en chaos. Des préparatifs jusqu\'à l\'aube, nous allons vivre les coulisses de cette soirée à travers le regard de ceux qui travaillent et qui devront compter sur leur unique qualité commune : Le sens de la fête.',
            'poster' => '2017-le-sens-de-la-fete.png',
            'releasedAt' => '04 Oct 2017',
            'genres' => ['Famille'],
        ],
        [
            'slug' => '2009-avatar',
            'title' => 'Avatar',
            'plot' => 'Malgré sa paralysie, Jake Sully, un ancien marine immobilisé dans un fauteuil roulant, est resté un combattant au plus profond de son être. Il est recruté pour se rendre à des années-lumière de la Terre, sur Pandora, où de puissants groupes industriels exploitent un minerai rarissime destiné à résoudre la crise énergétique sur Terre. Parce que l\'atmosphère de Pandora est toxique pour les humains, ceux-ci ont créé le Programme Avatar, qui permet à des \' pilotes \' humains de lier leur esprit à un avatar, un corps biologique commandé à distance, capable de survivre dans cette atmosphère létale. Ces avatars sont des hybrides créés génétiquement en croisant l\'ADN humain avec celui des Na\'vi, les autochtones de Pandora. Sous sa forme d\'avatar, Jake peut de nouveau marcher. On lui confie une mission d\'infiltration auprès des Na\'vi, devenus un obstacle trop conséquent à l\'exploitation du précieux minerai. Mais tout va changer lorsque Neytiri, une très belle Na\'vi, sauve la vie de Jake...',
            'poster' => '2009-avatar.png',
            'releasedAt' => '16 Dec 2009',
            'genres' => [],
        ],
        [
            'slug' => '2015-une-merveille-histoire-du-temps',
            'title' => 'Une Merveilleuse Histoire du Temps',
            'plot' => '1963, en Angleterre, Stephen, brillant étudiant en Cosmologie à l’Université de Cambridge, entend bien donner une réponse simple et efficace au mystère de la création de l’univers. De nouveaux horizons s’ouvrent quand il tombe amoureux d’une étudiante en art, Jane Wilde. Mais le jeune homme, alors dans la fleur de l’âge, se heurte à un diagnostic implacable : une dystrophie neuromusculaire plus connue sous le nom de maladie de Charcot va s’attaquer à ses membres, sa motricité, et son élocution, et finira par le tuer en l’espace de deux ans. Grâce à l’amour indéfectible, le courage et la résolution de Jane, qu’il épouse contre toute attente, ils entament tous les deux un nouveau combat afin de repousser l’inéluctable. Jane l’encourage à terminer son doctorat, et alors qu’ils commencent une vie de famille, Stephen, doctorat en poche va s’attaquer aux recherches sur ce qu’il a de plus précieux : le temps. Alors que son corps se dégrade, son cerveau fait reculer les frontières les plus éloignées de la physique. Ensemble, ils vont révolutionner le monde de la médecine et de la science, pour aller au-delà de ce qu’ils auraient pu imaginer : le vingt et unième siècle.',
            'poster' => '2015-une-merveille-histoire-du-temps.png',
            'releasedAt' => '21 Jan 2015',
            'genres' => ['Biopic', 'Drame'],
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::MOVIES as $movieRaw) {
            $movie = (new Movie())
                ->setTitle($movieRaw['title'])
                ->setPlot($movieRaw['plot'])
                ->setPoster($movieRaw['poster'])
                ->setSlug($movieRaw['slug'])
                ->setReleasedAt(new DateTimeImmutable($movieRaw['releasedAt']))
            ;

            foreach ($movieRaw['genres'] as $genreName) {
                $movie->addGenre($this->getGenre($genreName));
            }

            $manager->persist($movie);
        }

        $manager->flush();
    }

    private function getGenre(string $genreName): Genre
    {
        return $this->getReference("Genre.{$genreName}");
    }

    public function getDependencies(): array
    {
        return [
            GenreFixtures::class,
        ];
    }
}
