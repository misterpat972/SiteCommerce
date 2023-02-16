<?php

namespace App\Repository;

use App\Classe\Search;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function save(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByOne($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

/**
 * @return Product[] Returns an array of Product objects
 */
public function findWithSearch(Search $search) {
    // je crée une requête qui récupère tous les produits
    $query = $this
    // je crée une requête qui récupère tous les produits et les catégories
        ->createQueryBuilder('p')
        // je fais ici un select 
        ->select('c', 'p')
        // je récupère les catégories des produits récupérés dans la requête précédente (jointure)
        ->join('p.category', 'c');

    if(!empty($search->categories)) {
        // je récupère la requête et je filtre sur les catégories sélectionnées dans le formulaire
        $query = $query
        // je récupère la requête et je filtre sur les catégories sélectionnées dans le formulaire
            ->andWhere('c.id IN (:categories)')
            // je passe les catégories sélectionnées dans la requête SQL sous forme de tableau
            ->setParameter('categories', $search->categories);
    }

    if(!empty($search->string)) {
        // je récupère la requête
        $query = $query
        // recherche exacte sur le nom du produit ou sur la description
            ->andWhere('p.name LIKE :string')
            // recherche partiel
            ->setParameter('string', "%{$search->string}%");
    }
    // je retourne le résultat de la requête sous forme de tableau d'objets
    return $query->getQuery()->getResult();
}

}
