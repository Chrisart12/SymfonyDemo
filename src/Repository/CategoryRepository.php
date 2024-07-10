<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, Category::class);
    }
    
    
    public function findAllWithCount(int $page, int $nberPerPage): PaginationInterface
    {
        // return $this->createQueryBuilder('c')
        //             ->select('c as category', 'COUNT(c.id) as total')
        //             ->leftJoin('c.recipes', 'r')
        //             ->groupBy('c.id')
        //             ->getQuery()
        //             ->getResult();


        // return $this->paginator->paginate(
        //                 $this->createQueryBuilder('c')
        //                 ->select('c as category', 'COUNT(c.id) as total')
        //                 ->leftJoin('c.recipes', 'r')
        //                 ->groupBy('c.id')
        //                 ->getQuery()
        //                 ->getResult(),
        //                 $page,
        //                 $nberPerPage,
        //                 [
        //                     'distinct' => true,
        //                     // 'sortFieldAllowList' => ['c.id', 'r.title', 'r.content', 'r.createdAt'] // Permet de limiter le sortable pour des problèmes de sécurité
        //                 ]
        //             );

        return $this->paginator->paginate(
                        $this->createQueryBuilder('c')
                        ->select('NEW App\\DTO\\CategoryWithCountDTO(c.id, c.name, c.createdAt, COUNT(c.id))')
                        ->leftJoin('c.recipes', 'r')
                        ->groupBy('c.id')
                        ->getQuery()
                        ->getResult(),
                        $page,
                        $nberPerPage,
                        [
                            'distinct' => true,
                            'sortFieldAllowList' => ['c.id', 'c.name', 'c.count', 'c.createdAt'] // Permet de limiter le sortable pour des problèmes de sécurité
                        ]
                    );

    }

    //    /**
    //     * @return Category[] Returns an array of Category objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Category
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
