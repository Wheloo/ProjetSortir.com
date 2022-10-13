<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Participant;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @extends ServiceEntityRepository<Sortie>
 *
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

    public function add(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findSearch(SearchData $search, Participant $user): array
    {

        $query = $this
            ->createQueryBuilder('sortie')
            ->leftJoin('sortie.participants','participants')
            ->addSelect('participants')
            ->leftJoin('sortie.etat', 'etat')
            ->addSelect('etat');

        if($search->organisateur){
            $query->where('sortie.organisateur = :user')
                ->setParameter('user', $user)
            ;}
        if($search->inscrit){
            $query->where(':user MEMBER OF sortie.participants')
                ->setParameter('user', $user)
            ;}
        if($search->non_inscrit) {
            $query->where(':user NOT MEMBER OF sortie.participants')
                ->setParameter('user', $user)
            ;}
        if($search->passees){
            $query->setParameter('Passee', 'Passee')
                ->where('etat.libelle = :Passee');
        }
        if($search->campus) {
            $query->andWhere('sortie.campusOrganisateur = :campus')
                ->setParameter('campus',$search->campus)
            ;}

        if(!empty($search->search)) {
            $query->andWhere('sortie.nom LIKE :sortie' )
                ->setParameter('sortie', "%{$search->search}%" )
        ;}

        if($search->dateDebut) {
            $query->andWhere('sortie.dateHeureDebut >= :dateDebut')
                ->setParameter('dateDebut', $search->dateDebut);
            if($search->dateFin) {
                $query->andWhere('sortie.dateHeureDebut <= :dateFin')
                    ->setParameter('dateFin', $search->dateFin)
            ;}
        }

        return $query->getQuery()->getResult();
    }

}
