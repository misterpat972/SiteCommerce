<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function save(Order $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Order $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Order[] Returns an array of Order objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Order
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


    // permet de récupérer les commandes validées dans l'espace utilisateur
    public function findSuccessOrders($user): array
    {   // retourne les commandes validées par l'utilisateur
        return $this->createQueryBuilder('o')
        // on récupère les commandes qui ont un statut à 1
            ->andWhere('o.isPaid = 1')
            // on récupère les commandes qui ont un user qui correspond à l'utilisateur connecté
            ->andWhere('o.user = :user')
            // on définit le paramètre user qui correspond à l'utilisateur connecté
            ->setParameter('user', $user)
            // on trie les commandes par date de création
            ->orderBy('o.id', 'DESC')
            // on récupère les résultats
            ->getQuery()
            // on retourne les résultats
            ->getResult()
        ;
    }
}
