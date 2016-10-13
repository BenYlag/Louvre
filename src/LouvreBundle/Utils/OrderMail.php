<?php

namespace LouvreBundle\Utils;

use LouvreBundle\Entity\Commande;
use Symfony\Component\Templating\EngineInterface;

class OrderMail
{
    protected $mailer;
    protected $templating;
    private $from = "louvre@blagache.com";
    private $reply = "louvre@blagache.com";
    private $name = "Louvre Ticketing Service";

    public function __construct($mailer, EngineInterface $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    protected function sendMessage($to, $subject, $body)
    {
        $mail = \Swift_Message::newInstance();
        $cid = $mail->embed(\Swift_Image::fromPath('./img/logo.png'));
        $mail
            ->setFrom($this->from,$this->name)
            ->setTo($to)
            ->setSubject($subject)
            ->setBody( $this->templating->render('LouvreBundle:order:mail.html.twig', array('commande' => $body, 'image' => $cid)))
            ->setReplyTo($this->reply,$this->name)
            ->setContentType('text/html');

        $this->mailer->send($mail);
    }

    public function sendMail(Commande $commande){
        $subject = "Order " . $commande->getName() . " confirmation";
      //  $template = 'LouvreBundle:order:mail.html.twig';
        $to = $commande->getEmail();
       // $body = $this->templating->render('LouvreBundle:order:mail.html.twig', array('commande' => $commande));
        $this->sendMessage($to, $subject, $commande);
    }
}