<?php
namespace Tests\LouvreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class LouvreControllerTest extends WebTestCase
{

    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Louvre")')->count()
        );
}
    public function testErrorResumeOrder()
    {
        $client = self::createClient();
        $client->request('GET', '/fr/order/resume/UN27587');

        $this->assertTrue($client->getResponse()->isNotFound());
    }

    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlProvider()
    {
        return array(
            array('/'),
            array('/fr/'),
            array('/en/order'),
            //array('/fr/order/resume/UN275876'),
        );
    }
}