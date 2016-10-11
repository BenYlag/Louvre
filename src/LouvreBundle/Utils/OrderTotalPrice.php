<?php

namespace LouvreBundle\Utils;

use Doctrine\ORM\EntityManager;
use LouvreBundle\Entity\Commande;

class OrderTotalPrice
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    public function calculatePrice(Commande $commande)
    {
        $totalPrice = 0;
        foreach ($commande->getTickets() as $ticket) {
            $totalPrice = $totalPrice + $ticket->getPrice()->getValue();
        }
        if ($commande->getDuree()) {
            $totalPrice = $totalPrice / 2;
        }

        return $totalPrice * 100;
    }

}