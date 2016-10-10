<?php

namespace LouvreBundle\Utils;

use LouvreBundle\Entity\Commande;

class OrderMail
{

    public function sendMail(Commande $commande)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom('send@example.com')
            ->setTo('recipient@example.com')
            ->setBody(
                $this->render(
                // app/Resources/views/Emails/registration.html.twig
                    'second.html.twig',
                    array('commande' => $commande)
                ),
                'text/html'
            )
        ;
        $this->get('mailer')->send($message);
    }

}