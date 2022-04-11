<?php

namespace App\Repository;

use App\Entity\House;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method House|null find($id, $lockMode = null, $lockVersion = null)
 * @method House|null findOneBy(array $criteria, array $orderBy = null)
 * @method House[]    findAll()
 * @method House[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HouseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, House::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(House $entity, bool $flush = true): void
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
    public function remove(House $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


    public function findAvailbleHouses($start, $end , $nbr_voyagers,$type): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT DISTINCT house.id, house.lat, house.lng 
                FROM `house`
                INNER JOIN `event` ON event.house_id = house.id
                AND house.nbr_accepted >= :nbr_voyagers
                AND type = :type
                LEFT JOIN `reservation` ON reservation.house_id = house.id
                AND event.start_at <= :start
                AND event.end_at >= :end 
                AND house.type = :type
                AND reservation.start_date > :end 
                AND reservation.end_date < :start';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['start' => $start, 'end' => $end ,'nbr_voyagers' => $nbr_voyagers, 'type' => $type]);

        
        return $resultSet->fetchAllAssociative();
    }
   
    /*
    public function findOneBySomeField($value): ?House
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    
}
