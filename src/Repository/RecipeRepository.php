<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Recipe>
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, Recipe::class);
    }

    public function paginateRecipes(int $page, int $nberPerPage): PaginationInterface
    {

        
        return $this->paginator->paginate(
            $this->createQueryBuilder('r'),
            $page,
            $nberPerPage,
            [
                'distinct' => true,
                'sortFieldAllowList' => ['r.id', 'r.title', 'r.content', 'r.createdAt'] // Permet de limiter le sortable pour des problèmes de sécurité
            ]
        );
    }

    /**
     * Undocumented function
     *
     * @param integer $duration
     * @return array
     */
    public function findWithDurationLowerThan(int $duration): array
    {
        return $this->createQueryBuilder('r')
            ->select('r', 'c')
            ->where('r.duration <= :duration')
            ->orderBy('r.duration', 'ASC')
            ->leftJoin('r.category', 'c')
            // ->setMaxResults(1)
            ->setParameter('duration', $duration)
            ->getQuery()
            ->getResult();
    }

    /**
     * Undocumented function
     *
     * @return integer
     */
    public function findTotalDuration() 
    {
        return $this->createQueryBuilder('r')
        ->select('SUM(r.duration) as total')
        ->getQuery()
        ->getSingleScalarResult();
    }


    //    /**
    //     * @return Recipe[] Returns an array of Recipe objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Recipe
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
