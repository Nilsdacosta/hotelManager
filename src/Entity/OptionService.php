<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OptionServiceRepository")
 */
class OptionService
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *      message="La valeur ne peut être vide")
     */
    private $nomOption;

    /**
     * @ORM\Column(type="date")
     * @Assert\Date
     * @var string A "Y-m-d" formatted value
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="float")
     * @Assert\Type(
     *     type="float",
     *     message="La valeur {{ value }} doit être de type {{ type }}"
     * )
     */
    private $prixOption;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Employe", inversedBy="optionServices")
     * @ORM\JoinColumn(nullable=false)
     */
    private $employe;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tva", inversedBy="optionServices")
     * @ORM\JoinColumn(nullable=true)
     */
    private $tva;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Reservation", mappedBy="optionService")
     */
    private $reservations;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\AssignationMenage", mappedBy="optionService")
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

    public function getNomOption(): ?string
    {
        return $this->nomOption;
    }

    public function setNomOption(string $nomOption): self
    {
        $this->nomOption = $nomOption;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getPrixOption(): ?float
    {
        return $this->prixOption;
    }

    public function setPrixOption(float $prixOption): self
    {
        $this->prixOption = $prixOption;

        return $this;
    }

    public function getEmploye(): ?Employe
    {
        return $this->employe;
    }

    public function setEmploye(?Employe $employe): self
    {
        $this->employe = $employe;

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
            $reservation->addOptionService($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->contains($reservation)) {
            $this->reservations->removeElement($reservation);
            $reservation->removeOptionService($this);
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
            $assignationMenage->addOptionService($this);
        }

        return $this;
    }

    public function removeAssignationMenage(AssignationMenage $assignationMenage): self
    {
        if ($this->assignationMenages->contains($assignationMenage)) {
            $this->assignationMenages->removeElement($assignationMenage);
            $assignationMenage->removeOptionService($this);
        }

        return $this;
    }

    
}
