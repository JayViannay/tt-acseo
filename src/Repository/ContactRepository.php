<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<Contact>
 *
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    public function paginateContacts(Request $request): Paginator
    {
        return new Paginator(
            $this->createQueryBuilder('c')
                ->orderBy('c.createdAt', 'DESC')
                ->where('c.isArchived = false')
                ->getQuery()
                ->setFirstResult($request->query->getInt('offset', 0))
                ->setMaxResults($request->query->getInt('limit', 10))
        );
    }

    public function paginateContactsArchived(Request $request): Paginator
    {
        return new Paginator(
            $this->createQueryBuilder('c')
                ->orderBy('c.createdAt', 'DESC')
                ->where('c.isArchived = true')
                ->getQuery()
                ->setFirstResult($request->query->getInt('offset', 0))
                ->setMaxResults($request->query->getInt('limit', 10))
        );
    }
}
