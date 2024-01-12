<?php

namespace App\Repository;

use App\Entity\Department;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Department>
 *
 * @method Department|null find($id, $lockMode = null, $lockVersion = null)
 * @method Department|null findOneBy(array $criteria, array $orderBy = null)
 * @method Department[]    findAll()
 * @method Department[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepartmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Department::class);
    }

    public function getQB(
        ?string $orderBy = null,
        ?string $direction = null,
    ): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('d');
        $queryBuilder->andWhere('d.status = :one')->setParameter('one', 1);
        if (!is_null($orderBy) && !is_null($direction)) {
            $queryBuilder->addOrderBy('d.'.$orderBy, $direction);
        } else {
            $queryBuilder->addOrderBy('d.name', 'asc');
        }
        return $queryBuilder;
	}
}
