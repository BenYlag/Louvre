<?php

namespace LouvreBundle\Utils;

use Doctrine\ORM\EntityManager;


class TicketPrice
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param $birthdate
     * @param $discount
     * @return mixed
     */
    public function pricing(\DateTime $birthdate, $discount)
    {
        $repository = $this->em->getRepository('LouvreBundle:Price');

        if (!$discount) {

            $to = new \DateTime('today');
            $age = $birthdate->diff($to)->y;

            $price = $repository->findPriceForAge($age);
        }
        else {
            $price = $repository->findOneByName("reduit");
        }
        return $price;
    }

}
