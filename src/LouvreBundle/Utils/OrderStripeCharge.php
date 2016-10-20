<?php

namespace LouvreBundle\Utils;

use LouvreBundle\Entity\Commande;

class OrderStripeCharge
{
    private $stripekey;
    public function __construct($stripekey)
    {
     $this->stripekey = $stripekey;
    }

    public function orderCharge(Commande $commande, $orderAmount, $token)
    {
        // Set your secret key: remember to change this to your live secret key in production
        // See your keys here: https://dashboard.stripe.com/account/apikeys
        \Stripe\Stripe::setApiKey($this->stripekey);

        // Get the credit card details submitted by the form


        // Create a charge: this will charge the user's card
        try {
            \Stripe\Charge::create(array(
                "amount" => $orderAmount, // Amount in cents
                "currency" => "eur",
                "source" => $token,
                "description" => $commande->getName(),
            ));
            $status = "ok";
        } catch(\Stripe\Error\Card $e) {
            // The card has been declined
            $status = "pb";
        }

        return $status;
    }

}
