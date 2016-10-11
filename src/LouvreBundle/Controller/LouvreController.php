<?php

namespace LouvreBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use LouvreBundle\Entity\Commande;
use LouvreBundle\Form\CommandeType;
use LouvreBundle\Entity\Ticket;


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
     * @Route("/order", name="createOrder")
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
                    ->pricing($ticket->getBirth(), $ticket->getDiscount());
                $ticket->setCommande($commande);
                $ticket->setPrice($price);
            }

            $em->persist($commande);
            $em->flush();

            return $this->redirectToRoute('resumeOrder', array(
                'name' => $commande->getName(),
            ));

        }

        return $this->render('LouvreBundle:order:first.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/order/resume/{name}", name="resumeOrder")
     */
    public function orderResumeAction(Request $request, Commande $commande)
    {
        return $this->render('LouvreBundle:order:second.html.twig', array(
            'commande' => $commande,
        ));
    }
    /**
     * @Route("/order/edit/{name}", name="editOrder")
     */
    public function orderEditAction(Request $request, Commande $commande)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->get('form.factory')->create(CommandeType::class,$commande);

        $originalTickets = new ArrayCollection();

        foreach ($commande->getTickets() as $ticket){
            $originalTickets->add($ticket);
        }

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

        foreach ($originalTickets as $ticket) {
            if (false === $commande->getTickets()->contains($ticket)) {
                $em->remove($ticket);
            }
        }

            $tickets = $form->get('tickets')->getData();
          foreach ($tickets as $ticket) {
                $price = $this
                    ->container
                    ->get('louvre.ticketprice')
                    ->pricing($ticket->getBirth(), $ticket->getDiscount());
                $ticket->setCommande($commande);
                $ticket->setPrice($price);
            }

            $em->persist($commande);
            $em->flush();

            return $this->redirectToRoute('resumeOrder', array(
                'name' => $commande->getName(),
            ));
        }

        return $this->render('LouvreBundle:order:first.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    /**
     * @Route("/order/payment/{name}", name="payOrder")
     */
    public function orderPayAction(Request $request, Commande $commande)
    {
        if ($request->isMethod('POST')) {
            $orderAmount = $this
                ->container
                ->get('louvre.ordertotalprice')
                ->calculatePrice($commande);

            // Set your secret key: remember to change this to your live secret key in production
            // See your keys here: https://dashboard.stripe.com/account/apikeys
            \Stripe\Stripe::setApiKey($this->getParameter('stripekey'));

            // Get the credit card details submitted by the form
            $token = $_POST['stripeToken'];

            // Create a charge: this will charge the user's card
            try {
                $charge = \Stripe\Charge::create(array(
                    "amount" => $orderAmount, // Amount in cents
                    "currency" => "eur",
                    "source" => $token,
                    "description" => $commande->getName(),
                ));
                $test = 'ok';
            } catch(\Stripe\Error\Card $e) {
                // The card has been declined
                $test = 'pb';
            }

            return $this->render('LouvreBundle:order:third.html.twig', array(
                'commande' => $commande,
                'test' => $test,
            ));
        }
        return $this->render('LouvreBundle:order:third.html.twig', array(
            'commande' => $commande,
        ));
    }

}
