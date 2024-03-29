<?php

namespace App\Repository;

use App\Entity\Products;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Products|null find($id, $lockMode = null, $lockVersion = null)
 * @method Products|null findOneBy(array $criteria, array $orderBy = null)
 * @method Products[]    findAll()
 * @method Products[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Products::class);
    }

    // /**
    //  * @return Products[] Returns an array of Products objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */



    public function getProductFromId($id)
    {
        $query = $this->getEntityManager()->createQuery('SELECT p FROM App:Products p WHERE p.id = ?1');
        $query->setParameter(1, $id);
        return $query->getResult()[0];
    }


    public function getFromId($id): array
    {
        $query = $this->getEntityManager()->createQuery('SELECT p FROM App:Products p WHERE p.id = ?1');
        $query->setParameter(1, $id);
        return $query->getResult();
    }


    public function findOneBySomeField($value): ?Products
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }


    function getAllProducts()
    {
        $query = $this->getEntityManager()->createQuery('SELECT p FROM App:Products p');
        return $query->getResult();
    }
}
