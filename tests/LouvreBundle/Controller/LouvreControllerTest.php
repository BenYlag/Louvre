<?php
namespace Tests\LouvreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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
          //  array('/fr/order/resume/UN275876'),
            array('/fr/consult'),
        );
    }


    public function testOrderSearch()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/fr/');


        $link = $crawler
            ->filter('a:contains("billets")')
            ->eq(0)
            ->link()
        ;
        $crawler = $client->click($link);

        $buttonCrawlerNode = $crawler->selectButton('formSend');
        $form = $buttonCrawlerNode->form();


        $form['commande[date]'] = '28/10/2016';
        $form['commande[email]'] = 'contact@blagache.com';

        $values = $form->getPhpValues();

        $values['commande']['tickets'][0]['name'] = 'Lagache';
        $values['commande']['tickets'][0]['surname'] = 'Benoit';
        $values['commande']['tickets'][0]['birth'] = '31/08/1982';
        $values['commande']['tickets'][0]['country'] = 'FR';

        $crawler = $client->request($form->getMethod(), $form->getUri(), $values,
            $form->getPhpFiles());
        $crawler = $client->followRedirect();
        // dump($crawler);
        if ($this->assertEquals(1, $crawler->filter('html:contains("Récapitulatif")')->count())) {
            echo 'Formulaire validé - >page recapitulatif';
        }



        $link = $crawler
            ->filter('a:contains("commande")')
            ->eq(0)
            ->link()
        ;
        $crawler = $client->click($link);
        if ($this->assertEquals(1, $crawler->filter('html:contains("naissance")')->count())) {
            echo 'Retour -> formulaire via bouton';
        }
        // dump($crawler);
        // $this->assertEquals(1, $crawler->filter('html:contains("naissance")')->count());
        $buttonCrawlerNode = $crawler->selectButton('formSend');
        $form = $buttonCrawlerNode->form();
        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();
        // $this->assertEquals(1, $crawler->filter('html:contains("Récapitulatif")')->count());
        if ($this->assertEquals(1, $crawler->filter('html:contains("Récapitulatif")')->count())) {
            echo 'Formulaire validé - >page recapitulatif';
        }

        //  $form = $crawler->filter('form[name=payerStripe]')->form();

        // $crawler = $client->submit($form);

        // $this->assertEquals(1, $crawler->filter('html:contains("Card number")')->count());


      /*  $buttonCrawlerNode = $crawler->selectButton('payerStripe');
        $form = $buttonCrawlerNode->form();

        $crawler = $client->submit($form);
        dump($crawler);*/


    }
    public function testOrderFailEmail()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/fr/');


        $link = $crawler
            ->filter('a:contains("billets")')
            ->eq(0)
            ->link()
        ;
        $crawler = $client->click($link);

        $buttonCrawlerNode = $crawler->selectButton('formSend');
        $form = $buttonCrawlerNode->form();


        $form['commande[date]'] = '28/10/2016';
        $form['commande[email]'] = 'contact';

        $values = $form->getPhpValues();

        $values['commande']['tickets'][0]['name'] = 'Lagache';
        $values['commande']['tickets'][0]['surname'] = 'Benoit';
        $values['commande']['tickets'][0]['birth'] = '31/08/1982';
        $values['commande']['tickets'][0]['country'] = 'FR';

        $crawler = $client->request($form->getMethod(), $form->getUri(), $values,
            $form->getPhpFiles());
      //  $crawler = $client->followRedirect();
        // dump($crawler);
      $this->assertEquals(1, $crawler->filter('html:contains("est pas valide...")')->count());

    }
    public function testOrderFailTicketName()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/fr/');


        $link = $crawler
            ->filter('a:contains("billets")')
            ->eq(0)
            ->link()
        ;
        $crawler = $client->click($link);

        $buttonCrawlerNode = $crawler->selectButton('formSend');
        $form = $buttonCrawlerNode->form();


        $form['commande[date]'] = '28/10/2016';
        $form['commande[email]'] = 'contact@blagache.com';

        $values = $form->getPhpValues();

        $values['commande']['tickets'][0]['name'] = 'L'; // l'Erreur est ici !
        $values['commande']['tickets'][0]['surname'] = 'Benoit';
        $values['commande']['tickets'][0]['birth'] = '31/08/1982';
        $values['commande']['tickets'][0]['country'] = 'FR';

        $crawler = $client->request($form->getMethod(), $form->getUri(), $values,
            $form->getPhpFiles());
        //  $crawler = $client->followRedirect();
        // dump($crawler);
$this->assertEquals(1, $crawler->filter('html:contains("caractères")')->count());


    }

}