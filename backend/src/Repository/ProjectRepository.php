<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    /**
     * @return Project[]
     */
    public function findExpiredProjects(\DateTimeInterface $date): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.endDate < :date')
            ->andWhere('p.state = :state')
            ->setParameter('date', $date)
            ->setParameter('state', 'published')
            ->getQuery()
            ->getResult()
        ;
    }
}
