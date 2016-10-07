<?php

namespace LouvreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LouvreBundle\Entity\Price;

class LoadPrice implements FixtureInterface
{
    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
    public function load(ObjectManager $manager)
    {
        // Liste des prix a ajouter dans la base
        $prices = array(
            1 => array('name'=>'normal', 'value'=>16, 'ageMin'=>12, 'ageMax'=>60),
            2 => array('name'=>'enfant', 'value'=>8, 'ageMin'=>4, 'ageMax'=>12),
            3 => array('name'=>'gratuit', 'value'=>0,'ageMin'=>0, 'ageMax'=>4),
            4=> array('name'=>'senior', 'value'=>12,'ageMin'=>60, 'ageMax'=>150),
            5=> array('name'=>'reduit', 'value'=>10, 'ageMin'=>0, 'ageMax'=>0),
        );

        foreach ($prices as $element) {
            $price = new Price();
            foreach ($element as $cle => $data) {
                $laCle = 'set'.$cle;
                $price->$laCle($data);
            }
            $manager->persist($price);
            $manager->flush();
        }

    }
}