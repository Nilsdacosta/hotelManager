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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AssignationMenage", mappedBy="chambre")
     */
    private $assignationMenages;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->assignationMenages = new ArrayCollection();
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

    public function getRenderEtat(): ?string
    {
        if ($this->etat == 1){
            return "A blanc";
        }elseif($this->etat == 2){
            return "Recouche";
        }elseif($this->etat == 3){
            return "HS";
        }elseif($this->etat == 4){
            return "Sale";
        }else{
            return "Prête";
        }
    }


    public function getEtat(): ?int
    {
        $etat = $this->etat;
        $etat=1;
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

    public function getResume(): ?string
    {
       return $this->tronqueChaine($this->description,30);
       
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

    /**
     * @return Collection|AssignationMenage[]
     */
    public function getAssignationMenages(): Collection
    {
        return $this->assignationMenages;
    }

    public function addAssignationMenage(AssignationMenage $assignationMenage): self
    {
        if (!$this->assignationMenages->contains($assignationMenage)) {
            $this->assignationMenages[] = $assignationMenage;
            $assignationMenage->setChambre($this);
        }

        return $this;
    }

    public function removeAssignationMenage(AssignationMenage $assignationMenage): self
    {
        if ($this->assignationMenages->contains($assignationMenage)) {
            $this->assignationMenages->removeElement($assignationMenage);
            // set the owning side to null (unless already changed)
            if ($assignationMenage->getChambre() === $this) {
                $assignationMenage->setChambre(null);
            }
        }

        return $this;
    }



    public function tronqueChaine($chaine, $lg_max) 
{
    if (strlen($chaine) > $lg_max)
    {
        $chaine = substr($chaine, 0, $lg_max);
        $last_space = strrpos($chaine, " ");
        $chaine = substr($chaine, 0, $last_space)."...";
        
        return $chaine;
    }else{
        return $chaine;
    }
}
}
