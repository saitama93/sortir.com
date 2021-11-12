<?php

namespace App\Repository;

use App\Entity\Sortie;
use App\Entity\Utilisateur;
use App\Data\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }


    public function findSearch(SearchData $search, Utilisateur $user)
    {

        $query = $this
            ->createQueryBuilder('sortie')
            ->select('participants','organisateur', 'sortie')
            ->leftjoin('sortie.participants', 'participants')
            ->leftjoin('sortie.organisateur', 'organisateur');

            
        if (!empty($search->q)) {
            $query = $query
                ->andWhere('sortie.nom LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }


        if (!empty($search->sites)) {
            $query = $query
                ->andWhere('sortie.site = :site')
                ->setParameter('site', $search->sites);
        }
       

        if (!empty($search->participant)) {
            $query = $query
                ->andWhere(':user MEMBER OF sortie.participants')
                ->setParameter('user', $user);
        }

        if (!empty($search->organisateur)) {
            $query = $query
                ->andWhere(':user = organisateur')
                ->setParameter('user', $user);
        }

    

        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
