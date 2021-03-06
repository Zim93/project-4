<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Comment $entity, bool $flush = true): void
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
    public function remove(Comment $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function setHouseNote($house_id): void
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'UPDATE house  
        SET note = (
        SELECT AVG(note) 
        FROM COMMENT 
        WHERE house_id= :house_id)
        WHERE house.id = :house_id';
        
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['house_id' => $house_id]);
    }

    // /**
    //  * @return Comment[] Returns an array of Comment objects
    //  */
    // public function hasGuestAlreadyCommented($house_id,$guest_id)
    // {
    //     return $this->createQueryBuilder('c')
    //         ->andWhere('c.house_id = :house_id')
    //         ->setParameter('house_id', $house_id)
    //         ->andWhere('c.guest_id = :guest_id')
    //         ->setParameter('guest_id', $guest_id)
    //         ->getQuery()
    //         ->getOneOrNullResult()
    //     ;
    // }


    /*
    public function findOneBySomeField($value): ?Comment
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
