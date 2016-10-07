<?php

namespace LouvreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
    public function orderAction()
    {
        return $this->render('LouvreBundle:order:first.html.twig');
    }
}
