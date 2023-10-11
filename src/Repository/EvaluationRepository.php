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

    public function getQB(string $orderBy = 'e.created', string $orderDirection = 'DESC', string $criteria = null): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('e');
        $queryBuilder->andWhere('e.status = :one')
        ->setParameter('one', 1)
        ->orderBy($orderBy, $orderDirection);

        if ($criteria) {
            $queryBuilder->andWhere('e.requester.orgID LIKE :val')
                ->setParameter('val', $criteria);
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
