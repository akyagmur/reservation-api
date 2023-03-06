<?php

namespace App\Repository;

use App\Entity\Listing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Listing>
 *
 * @method Listing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Listing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Listing[]    findAll()
 * @method Listing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Listing::class);
    }

    public function save(Listing $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Listing $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // search listings by date range by available_from_date and available_to_date
    // other filters are rooms capacity has_wifi has_private_bathroom 
    // listing_type in ["apartment", "house", "villa", "condo", "cabin", "hostel", "hotel", "resort", "campsite", "boat"]

    /**
     * @return Listing[] Returns an array of Listing objects
     * fields on db : available_from_date available_to_date rooms capacity has_wifi has_private_bathroom listing_type
     */
    public function searchInListings($availableFromDate, $availableToDate, $rooms, $capacity, $hasWifi, $hasPrivateBathroom, $listingType): array
    {
        $qb = $this->createQueryBuilder('l');

        if ($availableFromDate) {
            $qb->andWhere('l.available_from_date >= :availableFromDate')
                ->setParameter('availableFromDate', $availableFromDate);
        }

        if ($availableToDate) {
            $qb->andWhere('l.available_to_date <= :availableToDate')
                ->setParameter('availableToDate', $availableToDate);
        }

        if ($rooms) {
            $qb->andWhere('l.rooms >= :rooms')
                ->setParameter('rooms', $rooms);
        }

        if ($capacity) {
            $qb->andWhere('l.capacity >= :capacity')
                ->setParameter('capacity', $capacity);
        }

        if ($hasWifi) {
            $qb->andWhere('l.has_wifi = :hasWifi')
                ->setParameter('hasWifi', $hasWifi);
        }

        if ($hasPrivateBathroom) {
            $qb->andWhere('l.has_private_bathroom = :hasPrivateBathroom')
                ->setParameter('hasPrivateBathroom', $hasPrivateBathroom);
        }

        if ($listingType) {
            $qb->andWhere('l.listing_type = :listingType')
                ->setParameter('listingType', $listingType);
        }
        
        return $qb->getQuery()->getResult();
    }

    public function findByReference($reference): ?Listing
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.reference = :reference')
            ->setParameter('reference', $reference)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

//    /**
//     * @return Listing[] Returns an array of Listing objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Listing
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
