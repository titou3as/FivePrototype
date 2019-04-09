<?php

namespace App\Repository;

use App\Entity\Contributor;
use App\Entity\Decision;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Contributor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contributor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contributor[]    findAll()
 * @method Contributor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContributorRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Contributor::class);
    }

    /**
     * @param $id
     * @return Contributor
     */
    public function findContributorNT($id){

       return $this->find($id)->getDecisions()->filter(function ($decision){
             return $decision->getIsTaken()==false;
            });

    }
   
    // /**
    //  * @return Contributor[] Returns an array of Contributor objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Contributor
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
