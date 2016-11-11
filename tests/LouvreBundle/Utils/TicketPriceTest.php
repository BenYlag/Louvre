<?php

namespace Tests\LouvreBundle\Utils;


use LouvreBundle\Utils\TicketPrice ;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class TicketPriceTest extends KernelTestCase
{
    private $em;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testPricing() {
       $operation = new TicketPrice($this->em);
        $date = new \DateTime("1982-08-31");

      $this->assertEquals("normal", $operation->pricing($date, false)->getName());
    }


    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }
}
