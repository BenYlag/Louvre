<?php

namespace LouvreBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use LouvreBundle\Entity\Commande;
use LouvreBundle\Form\CommandeType;



class LouvreController extends Controller
{

    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        return $this->render('LouvreBundle:home:index.html.twig');
    }

    /**
     * @Route("/order", name="createOrder")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function orderAction(Request $request)
    {
        $commande = new Commande();
        $form = $this->get('form.factory')->create(CommandeType::class,$commande);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $serial = $this->get('louvre.orderserial')->createSerial();
            $commande->setName($serial);

            $tickets = $form->get('tickets')->getData();
            foreach ($tickets as $ticket) {
                $price = $this
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
            ->get('louvre.daysoff')
            ->daysOff();


        return $this->render('LouvreBundle:order:first.html.twig', array(
            'form' => $form->createView(),
            'daysoff' => $daysOff,
        ));
    }

    /**
     * @Route("/order/edit/{name}", name="editOrder")
     * @param Request $request
     * @param Commande $commande
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
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
                $price = $this->get('louvre.ticketprice')
                    ->pricing($ticket->getBirth(), $ticket->getDiscount());
                $ticket->setCommande($commande);
                $ticket->setPrice($price);
            }
            $commande->setStatus($commande::COMMANDE_MODIFIED);
            $em->persist($commande);
            $em->flush();

            return $this->redirectToRoute('resumeOrder', array(
                'name' => $commande->getName(),
            ));
        }
        $daysOff = $this
            ->get('louvre.daysoff')
            ->daysOff();

        return $this->render('LouvreBundle:order:first.html.twig', array(
            'form' => $form->createView(),
            'commande' => $commande,
            'daysoff' => $daysOff,
        ));
    }


    /**
     * @Route("/order/resume/{name}", name="resumeOrder")
     * @param Request $request
     * @param Commande $commande
     * @return Response
     */
    public function orderResumeAction(Request $request, Commande $commande)
    {
        $orderAmount = $this
            ->container
            ->get('louvre.ordertotalprice')
            ->calculatePrice($commande);

        if ($request->isMethod('POST')) {
            $paiementResult = $this
                ->container
                ->get('louvre.orderstripecharge')
                ->orderCharge($commande, $orderAmount);

            $em = $this->getDoctrine()->getManager();

            if ($paiementResult == "ok") {
                $commande->setStatus($commande::COMMANDE_PAYED);
                $this->get('louvre.ordermail')->sendMail($commande);
            }
            else {
                $commande->setStatus($commande::COMMANDE_PAY_PB);
            }

            $em->persist($commande);
            $em->flush();

        }
        return $this->render('LouvreBundle:order:second.html.twig', array(
            'commande' => $commande,
            'orderAmount'=> $orderAmount,
        ));
    }

}
