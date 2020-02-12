<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
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
     * @Assert\Choice({"Double", "Single", "Twin", "Deluxe","Suite"}, message="Choississez une capacité valide.")
     */
    private $capacite;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Choice({1, 2, 3, 4}, message="Choississez un état valide.")
     */
    private $etat;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Assert\Type(
     *     type="float",
     *     message="La valeur {{ value }} doit être de type {{ type }}"
     * )
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *      message="La valeur ne peut être vide")
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
     * @ORM\OneToMany(targetEntity="App\Entity\AssignationMenage", mappedBy="chambre")
     */
    private $assignationMenages;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $statutAssignationMenage;

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
            return "Sale";
        }elseif($this->etat == 2){
            return "Recouche";
        }elseif($this->etat == 3){
            return "Prête";
        }elseif($this->etat == 4){
            return "HS";
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

    public function getStatutAssignationMenage(): ?int
    {
        return $this->statutAssignationMenage;
    }

    public function setStatutAssignationMenage(?int $statutAssignationMenage): self
    {
        $this->statutAssignationMenage = $statutAssignationMenage;

        return $this;
    }
}
