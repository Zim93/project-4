<?php

namespace App\Repository;

use App\Entity\Notification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Notification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notification[]    findAll()
 * @method Notification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Notification $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Notification $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // 
    //  * @return Notification[] Returns an array of Notification objects
    //  
    
    public function findByReservation($user)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.user_id = :user')
            ->andWhere('n.opened = "0"')
            ->andWhere('n.type = "reservation"')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByComment($user)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.user_id = :user')
            ->andWhere('n.opened = "0"')
            ->andWhere('n.type = "comment"')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?Notification
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
