<?php

namespace App\Repository;

use App\Entity\Nurses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Nurses>
 */
class NursesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Nurses::class);
    }

//    /**
//     * @return Nurses[] Returns an array of Nurses objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

public function getAll(): array
    {
        return $this->createQueryBuilder('n')
            ->getQuery()
            ->getResult();
    }

public function nurseLogin(string $name, string $pass) :array{
    return $this->createQueryBuilder("n")
    ->andWhere('n.user = :name', 'n.password = :pass')
        ->setParameter('name', $name)
        ->setParameter('pass', $pass)
        ->orderBy('n.id', 'ASC')
        ->getQuery()
        ->getResult()
    ;
}

public function findOneByName(string $name): ?Nurses
{
    return $this->createQueryBuilder('n')
        ->andWhere('n.user = :name')
        ->setParameter('name', $name)
        ->getQuery()
        ->getOneOrNullResult();
}

}
