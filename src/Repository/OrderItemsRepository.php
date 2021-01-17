<?php

namespace App\Repository;

use App\Entity\OrderItems;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderItems|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderItems|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderItems[]    findAll()
 * @method OrderItems[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderItemsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderItems::class);
    }

    // /**
    //  * @return OrderItems[] Returns an array of OrderItems objects
    //  */
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.order = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?OrderItems
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    function insertOrderItem($orderId, $productId, $quantity)
    {
        $entityManager = $this->getEntityManager();
        $orderItem = new OrderItems();
        $orderItem->setOrder($orderId);
        $orderItem->setProduct($productId);
        $orderItem->setQuantity($quantity);
        $entityManager->persist($orderItem);
        $entityManager->flush();
    }
}
