<?php

namespace App\Repository;

use App\Entity\Institution;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Institution>
 *
 * @method Institution|null find($id, $lockMode = null, $lockVersion = null)
 * @method Institution|null findOneBy(array $criteria, array $orderBy = null)
 * @method Institution[]    findAll()
 * @method Institution[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstitutionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Institution::class);
    }

    public function getQB(
        ?string $orderBy = null,
        ?string $direction = null,
        ?string $usState = null,
    ): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('i');
        $queryBuilder->andWhere('i.status = :one')->setParameter('one', 1);
        if (!is_null($usState)) {
            $queryBuilder->andWhere('i.state=:val1')->setParameter('val1', $usState);
        }
        if (!is_null($orderBy) && !is_null($direction)) {
            $queryBuilder->addOrderBy('i.'.$orderBy, $direction);
        } else {
            $queryBuilder->addOrderBy('i.name', 'asc');
        }
        return $queryBuilder;
	}
}
