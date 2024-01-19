<?php

namespace App\Repository;

use App\Entity\Course;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Course>
 *
 * @method Course|null find($id, $lockMode = null, $lockVersion = null)
 * @method Course|null findOneBy(array $criteria, array $orderBy = null)
 * @method Course[]    findAll()
 * @method Course[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Course::class);
    }

    public function getQB(
        ?string $orderBy = null,
        ?string $direction = null,
        ?string $subjCode = null,
    ): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('c');
        $queryBuilder->andWhere('c.status = :one')->setParameter('one', 1);
        if (!is_null($subjCode)) {
            $queryBuilder->andWhere('c.subjectCode=:val1')->setParameter('val1', $subjCode);
        }
        if (!is_null($orderBy) && !is_null($direction)) {
            $queryBuilder->addOrderBy('c.'.$orderBy, $direction);
        } else {
            $queryBuilder->addOrderBy('c.subjectCode', 'asc');
            $queryBuilder->addOrderBy('c.courseNumber', 'asc');
        }
        return $queryBuilder;
	}
}
