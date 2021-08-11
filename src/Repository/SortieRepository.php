<?php

namespace App\Repository;

use App\Entity\Participant;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
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

    /**
     * @return Sortie[]
     */
    public function findSorties(){

        $queryBuilder =$this->createQueryBuilder('s');

        $query = $queryBuilder->getQuery();
        $query->setMaxResults(30);

        $result = $query->getResult();

        return $result;
    }


    public function findIdAnnulSortie($id) {
        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder->leftJoin('s.organisateur', 'o')
            ->addSelect('o');
        $queryBuilder->leftJoin('o.campus', 'c')
            ->addSelect('c');
        $queryBuilder->leftJoin('s.etat', 'e')
            ->addSelect('e');
        $queryBuilder->leftjoin('s.lieu', 'l')
            ->addSelect('l');
        $queryBuilder->leftjoin('l.ville', 'v')
            ->addSelect('v');
        $queryBuilder->andWhere('s.id = :idV')
            ->setParameter('idV', $id);
        $query = $queryBuilder->getQuery();
        $query->setMaxResults(50);
        $resultat = $query->getResult();
        return $resultat;
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
