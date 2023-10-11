<?php

namespace App\Repository;

use App\Entity\Evaluation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Evaluation>
 *
 * @method Evaluation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evaluation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evaluation[]    findAll()
 * @method Evaluation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvaluationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evaluation::class);
    }

    public function getQB(
        string $orderBy = 'created', 
        string $direction = 'desc', 
        string $reqAdmin = null,
        string $phase = null,
        int $requesterID = null,
        int $assigneeID = null
    ): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('e');
        $queryBuilder->andWhere('e.status = :one')
        ->setParameter('one', 1)
        ->orderBy('e.'.$orderBy, $direction);

        if (!is_null($reqAdmin)) {$queryBuilder->andWhere('e.reqAdmin=:val1')->setParameter('val1', $reqAdmin);}
        if (!is_null($phase)) {$queryBuilder->andWhere('e.phase=:val2')->setParameter('val2', $phase);}
        if (!is_null($requesterID)) {
            $queryBuilder->andWhere('e.requester.id=:val3')->setParameter('val3', $requesterID);
        }
        if (!is_null($assigneeID)) {
            $queryBuilder->andWhere('e.assignee.id=:val4')->setParameter('val4', $assigneeID);
        }

        return $queryBuilder;
    }

    /**
     * @return Evaluation[] Returns an array of Evaluation objects
     */
    public function findByStatus($value): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.status = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(30)
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?Evaluation
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
