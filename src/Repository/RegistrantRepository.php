<?php

namespace App\Repository;

use App\Entity\Registrant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Registrant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Registrant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Registrant[]    findAll()
 * @method Registrant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegistrantRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Registrant::class);
    }

//    /**
//     * @return Registrant[] Returns an array of Registrant objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Registrant
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
