<?php

namespace App\Repository;

use App\Entity\Course;
use App\Entity\Evaluation;
use App\Entity\Institution;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use EcPhp\CasBundle\Security\Core\User\CasUser;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

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
        ?string $orderBy = null,
        ?string $direction = null,
        ?string $reqAdmin = null,
        ?string $phase = null,
        ?UserInterface $requester = null,
        ?UserInterface $assignee = null,
		?bool $bypass = false,
        ?Institution $institution = null,
    ): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('e');

        if ($bypass) {
            $queryBuilder->andWhere('e.id = :zero')->setParameter('zero', 0);
            return $queryBuilder;
        }

        $queryBuilder->andWhere('e.status = :one')->setParameter('one', 1);

        if (!is_null($reqAdmin)) {$queryBuilder->andWhere('e.reqAdmin=:val1')->setParameter('val1', $reqAdmin);}
        if (!is_null($phase)) {$queryBuilder->andWhere('e.phase=:val2')->setParameter('val2', $phase);}
        if (!is_null($requester)) {
            $queryBuilder->leftJoin('e.requester', 'r');
            $queryBuilder->andWhere('r.username=:val3')->setParameter('val3', $requester->getUserIdentifier());
        }
        if (!is_null($assignee)) {
            $queryBuilder->leftJoin('e.assignee', 'a');
            $queryBuilder->andWhere('a.username=:val4')->setParameter('val4', $assignee->getUserIdentifier());
        }
        if (!is_null($institution)) {
            $queryBuilder->leftJoin('e.institution', 'i');
            $queryBuilder->andWhere('i.id=:val5')->setParameter('val5', $institution->getId());
        }
        if (!is_null($orderBy) && !is_null($direction)) {
            $queryBuilder->addOrderBy('e.'.$orderBy, $direction);
        } else {
            $queryBuilder->addOrderBy('e.updated', 'desc');
        }

        return $queryBuilder;
	}

    public function getEvaluationsByCourse(
        ?string $orderBy = null,
        ?string $direction = null,
        ?string $reqAdmin = null,
        ?Course $course = null,
    ): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('e');
        $queryBuilder->andWhere('e.status = :one')->setParameter('one', 1);
        if (!is_null($reqAdmin)) {$queryBuilder->andWhere('e.reqAdmin=:val0')->setParameter('val0', $reqAdmin);}
        if (!is_null($course)) {
            $queryBuilder->andWhere('e.finalEquiv1Course=:val1')->setParameter('val1', $course);
            $queryBuilder->orWhere('e.finalEquiv2Course=:val2')->setParameter('val2', $course);
            $queryBuilder->orWhere('e.finalEquiv3Course=:val3')->setParameter('val3', $course);
            $queryBuilder->orWhere('e.finalEquiv4Course=:val4')->setParameter('val4', $course);
        }
        if (!is_null($orderBy) && !is_null($direction)) {
            $queryBuilder->addOrderBy('e.'.$orderBy, $direction);
        } else {
            $queryBuilder->addOrderBy('e.updated', 'desc');
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
