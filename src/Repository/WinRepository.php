<?php

namespace App\Repository;

use App\Entity\Win;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Win>
 *
 * @method Win|null find($id, $lockMode = null, $lockVersion = null)
 * @method Win|null findOneBy(array $criteria, array $orderBy = null)
 * @method Win[]    findAll()
 * @method Win[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WinRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Win::class);
    }

    /**
     * @return Win[]
     *
     * @throws \Exception
     */
    public function findByDate(\DateTimeInterface $date): array
    {
        return $this->createQueryBuilder('w')
            ->andWhere(':start_time <= w.createdAt')
            ->andWhere('w.createdAt < :end_time')
            ->orderBy('w.createdAt', 'DESC')
            ->getQuery()
            ->execute([
                'start_time' => new \DateTime($date->format(\DateTimeInterface::ATOM).' midnight'),
                'end_time' => new \DateTime($date->format(\DateTimeInterface::ATOM).' midnight next day'),
            ]);
    }
}
