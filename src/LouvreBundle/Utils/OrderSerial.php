<?php

namespace LouvreBundle\Utils;

use Doctrine\ORM\EntityManager;

class OrderSerial
{

    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function createSerial()
    {
        $template = 'XX999999';
        $k = strlen($template);
        $serial = '';
        for ($i = 0; $i < $k; $i++) {
            switch ($template[$i]) {
                case 'X':
                    $serial .= chr(rand(65, 90));
                    break;
                case '9':
                    $serial .= rand(0, 9);
                    break;
            }
        }

        $repository = $this->em->getRepository('LouvreBundle:Commande');
        $serialUsed = $repository->findOneByName($serial);

        while (!is_null($serialUsed)) {
            for ($i = 0; $i < $k; $i++) {
                switch ($template[$i]) {
                    case 'X':
                        $serial .= chr(rand(65, 90));
                        break;
                    case '9':
                        $serial .= rand(0, 9);
                        break;
                }
            }
            $serialUsed = $repository->findOneByName($serial);
        }
        return $serial;
    }

}
