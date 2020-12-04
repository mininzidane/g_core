<?php

namespace App\Repository;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /**
     * @return Task[]
     */
    public function findTodayTasks(User $user): array
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->andWhere('t.status = :status')
            ->andWhere('t.date = :today')
            ->andWhere('t.user = :user')
            ->setParameter('status', Task::STATUS_ACTIVE)
            ->setParameter('today', (new \DateTime())->format('Y-m-d'))
            ->setParameter('user', $user)
            ->orderBy('t.id', 'ASC')
        ;
        return $queryBuilder->getQuery()->getResult();
    }
}
