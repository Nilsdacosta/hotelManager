<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChambreRepository")
 */
class Chambre
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $capacite;

    /**
     * @ORM\Column(type="smallint")
     */
    private $etat;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tva", inversedBy="chambres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tva;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Reservation", mappedBy="chambre")
     */
    private $reservations;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\AssignationMenage", mappedBy="chambre", cascade={"persist", "remove"})
     */
    private $assignationMenage;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCapacite(): ?string
    {
        return $this->capacite;
    }

    public function setCapacite(string $capacite): self
    {
        $this->capacite = $capacite;

        return $this;
    }

    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(int $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTva(): ?Tva
    {
        return $this->tva;
    }

    public function setTva(?Tva $tva): self
    {
        $this->tva = $tva;

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->addChambre($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->contains($reservation)) {
            $this->reservations->removeElement($reservation);
            $reservation->removeChambre($this);
        }

        return $this;
    }

    public function getAssignationMenage(): ?AssignationMenage
    {
        return $this->assignationMenage;
    }

    public function setAssignationMenage(?AssignationMenage $assignationMenage): self
    {
        $this->assignationMenage = $assignationMenage;

        // set (or unset) the owning side of the relation if necessary
        $newChambre = null === $assignationMenage ? null : $this;
        if ($assignationMenage->getChambre() !== $newChambre) {
            $assignationMenage->setChambre($newChambre);
        }

        return $this;
    }
}
