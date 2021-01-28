<?php

namespace App\Repository;

use App\Entity\Orders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Orders|null find($id, $lockMode = null, $lockVersion = null)
 * @method Orders|null findOneBy(array $criteria, array $orderBy = null)
 * @method Orders[]    findAll()
 * @method Orders[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Orders::class);
    }

    // /**
    //  * @return Orders[] Returns an array of Orders objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Orders
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    public function getFromOrderId($id) {
        $query = $this->getEntityManager()->createQuery('SELECT o FROM App:Orders o WHERE o.id = ?1');
        $query->setParameter(1, $id);
        return $query->getResult()[0];
    }

    public function getOrderFromId($id) {
        $query = $this->getEntityManager()->createQuery('SELECT o FROM App:Orders o WHERE o.id = ?1');
        $query->setParameter(1, $id);
        return $query->getResult();
    }
    public function getFromId($userId): array
    {
        $query = $this->getEntityManager()->createQuery('SELECT o FROM App:Orders o WHERE IDENTITY(o.user) = ?1');
        $query->setParameter(1, $userId);
        return $query->getResult();
    }

    function getOrderIdFromUser($uid)
    {
        $query = $this->getEntityManager()->createQuery('SELECT o.id FROM App:Orders o WHERE IDENTITY(o.user) = ?1');
        $query->setParameter(1, $uid);
        $result = $query->getResult();
        if ($result) {
            return $query->getResult();
        } else {
            return '';
        }
    }



    function getOrderIdValueFromUser($uid)
    {
        $query = $this->getEntityManager()->createQuery('SELECT o.id FROM App:Orders o WHERE IDENTITY(o.user) = ?1');
        $query->setParameter(1, $uid);
        $result = $query->getResult();
        if ($result) {
            return $query->getResult()[0];
        } else {
            return '';
        }
    }


    public function insertOrder($user) {
        $entityManager = $this->getEntityManager();
        $order = new Orders();
        $order->setUser($user);
        $order->setCreatedAt(new \DateTime());
        $order->setStatus(1);
        $entityManager->persist($order);
        $entityManager->flush();
    }

    function updateTotal($total, $orderId)
    {
        $query = $this->getEntityManager()->createQuery('UPDATE App:Orders m SET m.total = ?1WHERE m.id = ?2');
        $query->setParameter(1, $total);
        $query->setParameter(2, $orderId);
        $query->getResult();
    }
}
