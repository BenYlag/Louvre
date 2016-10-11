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
        $daysOff = $this
            ->container
            ->get('louvre.daysoff')
            ->jours_feries_deux_ans(date('Y'));
        dump($daysOff);
        return $this->render('LouvreBundle:order:first.html.twig', array(
            'form' => $form->createView(),
            'daysoff' => $daysOff,
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
     * @Route("/order/resume/{name}", name="resumeOrder")
     */
    public function orderResumeAction(Request $request, Commande $commande)
    {
        $orderAmount = $this
            ->container
            ->get('louvre.ordertotalprice')
            ->calculatePrice($commande);

        if ($request->isMethod('POST')) {
            $PaiementResult = $this
                ->container
                ->get('louvre.orderstripecharge')
                ->orderCharge($commande, $orderAmount);

            return $this->render('LouvreBundle:order:second.html.twig', array(
                'commande' => $commande,
                'orderAmount'=>$orderAmount,
                'test' => $PaiementResult,
            ));
        }
        return $this->render('LouvreBundle:order:second.html.twig', array(
            'commande' => $commande,
            'orderAmount'=> $orderAmount,
        ));
    }

}
