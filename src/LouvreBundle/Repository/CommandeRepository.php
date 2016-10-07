<?php

namespace LouvreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * CommandeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommandeRepository extends EntityRepository
{
    public function getCommandeWithTicketAndPrice($id) {
        $qb = $this->createQueryBuilder('a');
        $qb
            ->innerJoin('a.tickets','c')
            ->addSelect('c');
        $qb
            ->innerJoin('c.price','d')
            ->addSelect('d');
        $qb
            ->where('a.id = :id')
            ->setParameter('id', $id);
        return $qb->getQuery()->getResult();
    }
}
