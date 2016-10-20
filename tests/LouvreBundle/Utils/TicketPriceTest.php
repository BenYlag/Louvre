<?php

namespace Tests\LouvreBundle\Utils;

use LouvreBundle\Utils\TicketPrice;
use LouvreBundle\Entity\Price;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

class TicketPriceTest extends \PHPUnit_Framework_TestCase
{

    public function testPricing() {

        // First, mock the object to be used in the test
        $price = $this->createMock(Price::class);
        $price->expects($this->once())->method('getName')->will($this->returnValue("normal"));
        $price->expects($this->once())->method('getValue')->will($this->returnValue(16));
        $price->expects($this->once())->method('getAgeMax')->will($this->returnValue(12));
        $price->expects($this->once())->method('getAgeMin')->will($this->returnValue(60));

        // Now, mock the repository so it returns the mock of the employee
        $priceRepository = $this->getMockBuilder(EntityRepository::class)->disableOriginalConstructor()->getMock();
        $priceRepository->expects($this->once())->method('findPriceForAge')->will($this->returnValue($price));

        // Last, mock the EntityManager to return the mock of the repository
        $entityManager = $this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock();
        $entityManager->expects($this->once())->method('getRepository')->will($this->returnValue($priceRepository));

        $operation = new TicketPrice($entityManager);
        $date = new \DateTime("1982-08-31");

      $this->assertEquals($price, $operation->pricing($date, false));
    }

}
