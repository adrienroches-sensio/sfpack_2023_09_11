<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Movie>
 *
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    /**
     * @return list<array{title: string, slug: string}>
     */
    public function listBySlugAndTitle(): array
    {
        $qb = $this->createQueryBuilder('movie');

        $qb
            ->select('movie.title')
            ->addSelect('movie.slug')
        ;

        return $qb->getQuery()->getResult();
    }

    /**
     * @return list<Movie>
     */
    public function listAll(): array
    {
        $qb = $this->createQueryBuilder('movie');

        $qb
            ->leftJoin('movie.genres', 'genre')
            ->addSelect('genre')
        ;

        return $qb->getQuery()->getResult();
    }

    public function getBySlug(string $slug): Movie
    {
        $qb = $this->createQueryBuilder('movie');

        $qb
            ->andWhere($qb->expr()->eq('movie.slug', ':slug'))
            ->setParameter('slug', $slug)
        ;

        return $qb->getQuery()->getSingleResult();
    }

    //    /**
    //     * @return Movie[] Returns an array of Movie objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;

    //    }
    //    public function findOneBySomeField($value): ?Movie
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
