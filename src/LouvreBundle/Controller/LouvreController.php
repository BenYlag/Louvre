<?php

namespace LouvreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use LouvreBundle\Entity\Commande;
use LouvreBundle\Form\CommandeType;

class LouvreController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('LouvreBundle:home:index.html.twig');
    }

    /**
     * @Route("/order")
     */
    public function orderAction(Request $request)
    {
        $commande = new Commande();
        $form = $this->get('form.factory')->create(CommandeType::class,$commande);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $serial = $this
                ->container
                ->get('louvre.orderserial')
                ->createSerial();
            $commande->setName($serial);
            $commande->setPayment('null');

            $tickets = $form->get('tickets')->getData();
            foreach ($tickets as $ticket) {
                $price = $this
                    ->container
                    ->get('louvre.ticketprice')
                    ->Pricing($ticket->getBirth(), $ticket->getDiscount());
                $ticket->setCommande($commande);
                $ticket->setPrice($price);
            }

            $em->persist($commande);
            $em->flush();

            return $this->render('LouvreBundle:order:first.html.twig', array(
                'form' => $form->createView(),
            ));
        }

        return $this->render('LouvreBundle:order:first.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
