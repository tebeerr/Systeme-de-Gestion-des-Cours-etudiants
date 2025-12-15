<?php

namespace App\Repository;

use App\Entity\Examen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Examen>
 */
class ExamenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Examen::class);
    }

    /**
     * @return Examen[]
     */
    public function findStudentsForExam(Examen $exam): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.cours = :cours')
            ->andWhere('e.Libelle = :libelle')
            ->andWhere('e.DateExamen = :dateExamen')
            ->andWhere('e.user IS NOT NULL')
            ->setParameter('cours', $exam->getCours())
            ->setParameter('libelle', $exam->getLibelle())
            ->setParameter('dateExamen', $exam->getDateExamen())
            ->orderBy('e.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Examen[] Returns an array of Examen objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Examen
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
