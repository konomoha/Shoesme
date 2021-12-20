<?php

namespace App\Repository;

use App\Entity\Chaussure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Chaussure|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chaussure|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chaussure[]    findAll()
 * @method Chaussure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChaussureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chaussure::class);
    }

    // /**
    //   * @return Chaussure[] Returns an array of Chaussure objects
    // */
    
    // public function findByCouleur($value)
    // {
    //     return $this->createQueryBuilder('c')
    //         ->andWhere('couleur = :val')
    //         ->setParameter('val', $value)
    //         ->orderBy('')
    //         ->setMaxResults('')
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }
    

    /*
    public function findOneBySomeField($value): ?Chaussure
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
