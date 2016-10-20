<?php

namespace LouvreBundle\Utils;

use Doctrine\ORM\EntityManager;

class DaysOff
{
    protected $em;
    protected $flood_Limit;

    public function __construct(EntityManager $em, $flood_Limit)
    {
        $this->em = $em;
        $this->flood_Limit = $flood_Limit;
    }
    public function joursFeries($annee)
    {
        /* URL            : http://www.phpsources.org/scripts641-PHP.htm  */
        $dimanche_paques = date("d-m-Y", easter_date($annee));
        $lundi_paques = date("d/m/Y", strtotime("$dimanche_paques +1 day"));
        $jeudi_ascension = date("d/m/Y", strtotime("$dimanche_paques +39 day"));
        $lundi_pentecote = date("d/m/Y", strtotime("$dimanche_paques +50 day"));

        $jours_feries = array(
            date("d/m/Y", strtotime($dimanche_paques)),
            $lundi_paques,
            $lundi_pentecote,
            $jeudi_ascension,
            "01/01/$annee",
            "01/05/$annee",
            "08/05/$annee",
            "15/05/$annee",
            "14/07/$annee",
            "11/11/$annee",
            "01/11/$annee",
            "25/12/$annee",
        );

        return $jours_feries;
    }

    public function joursFeriesDeuxAns($annee) {
        $jours_feries = array();
        for ($i=$annee;$i<=($annee + 2);$i++) {
            $jours_feries = array_merge($jours_feries, $this->joursFeries(strval($i)));
        }
        sort($jours_feries);
        return $jours_feries;
    }

    public function daysOff ($date=null) {
        if (is_null($date)) {
            $date = date('Y');
        }
        $daysOff = $this->joursFeriesDeuxAns($date);
        $repository = $this->em->getRepository('LouvreBundle:Commande');
        $fullDays = $repository->getDaysOverLimitTickets($this->flood_Limit);
        foreach ($fullDays as $key => $fullDay) {
            foreach ($fullDay as $cle => $valeur) {
                array_push($daysOff, date_format($valeur, 'd/m/Y'));
            }
        }

        return $daysOff;
    }
}
