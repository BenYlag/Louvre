<?php

namespace LouvreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Commande
 *
 * @ORM\Table(name="commande", indexes={@ORM\Index(name="date_idx", columns={"date"})})
 * @ORM\Entity(repositoryClass="LouvreBundle\Repository\CommandeRepository")
 */
class Commande
{
    const COMMANDE_STARTED = "starded";
    const COMMANDE_MODIFIED = "modified";
    const COMMANDE_PAYED = "pay_success";
    const COMMANDE_PAY_PB = "pay_failed";

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\Email(
     *     checkMX = true,
     *     message = "order.error.email",
     *     )
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     * @Assert\Range(
     *     min ="today",
     *     max ="+2 years",
     *     minMessage="order.error.mindate",
     *     maxMessage="order.error.maxdate"
     * )
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @var bool
     *
     * @ORM\Column(name="duree", type="boolean")
     */
    private $duree;

    /**
     * @ORM\OneToMany(targetEntity="LouvreBundle\Entity\Ticket", mappedBy="commande", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $tickets;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Commande
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Commande
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Commande
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }


    /**
     * Set duree
     *
     * @param boolean $duree
     *
     * @return Commande
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * Get duree
     *
     * @return bool
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tickets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->status = self::COMMANDE_STARTED;
    }

    /**
     * Add ticket
     *
     * @param \LouvreBundle\Entity\Ticket $ticket
     *
     * @return Commande
     */
    public function addTicket(Ticket $ticket)
    {
        $this->tickets[] = $ticket;

        $ticket->setCommande($this);

        return $this;
    }

    /**
     * Remove ticket
     *
     * @param \LouvreBundle\Entity\Ticket $ticket
     */
    public function removeTicket(Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context) {
        $leJour =$this->getDate()->format('N');
        if ($leJour == 2) {
            $context->buildViolation('order.error.tuesday')
                ->atPath('date')
                ->addViolation();
        }
        $maintenant = date("H");
        $maintenant = (int)$maintenant;
        $jourResa = $this->getDate();
        $jourResa = $jourResa->format('d/m/Y');
        $aujourdhui = new \DateTime('today');
        $aujourdhui = $aujourdhui->format('d/m/Y');

        if (($jourResa == $aujourdhui) && (!$this->getDuree()) && ($maintenant > 13)) {
            $context->buildViolation('order.error.hour')
                ->atPath('duree')
                ->addViolation();
        }
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Commande
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
}
