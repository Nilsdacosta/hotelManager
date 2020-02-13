<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $dateEntree;

    /**
     * @ORM\Column(type="date")
     */
    private $dateSortie;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Choice({1, 2, 3, 4}, message="Choississez un statut valide.")
     */
    private $status;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $carteBancaire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Chambre", inversedBy="reservations")
     */
    private $chambre;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\OptionService", inversedBy="reservations")
     */
    private $optionService;

    /**
     * @ORM\Column(type="date")
     */
    private $dateCreation;

    public function __construct()
    {
        $this->chambre = new ArrayCollection();
        $this->optionService = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateEntreepourCalendrier(): ?\DateTime
    {
        return $this->dateEntree;
    }

    public function getDateEntree(): ?\DateTimeInterface
    {
        return $this->dateEntree;
    }

    public function setDateEntree(\DateTimeInterface $dateEntree): self
    {
        $this->dateEntree = $dateEntree;

        return $this;
    }


    public function getDateSortiePourCalendrier(): ?\DateTime
    {
        return $this->dateSortie;
    }

    public function getDateSortie(): ?\DateTimeInterface
    {
        return $this->dateSortie;
    }

    public function setDateSortie(\DateTimeInterface $dateSortie): self
    {
        $this->dateSortie = $dateSortie;

        return $this;
    }

      public function getRenderStatus(): ?string
    {
        if ($this->status == 1){
            return "Réservée";
        }elseif($this->status == 2){
            return "Validée";
        }elseif($this->status == 3){
            return "Annulée";
        }else{
            return "Facturée";
        }
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }



    public function getCarteBancaire(): ?string
    {
        return $this->carteBancaire;
    }

    public function setCarteBancaire(?string $carteBancaire): self
    {
        $this->carteBancaire = $carteBancaire;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection|Chambre[]
     */
    public function getChambre(): Collection
    {
        return $this->chambre;
    }

    public function addChambre(Chambre $chambre): self
    {
        if (!$this->chambre->contains($chambre)) {
            $this->chambre[] = $chambre;
        }

        return $this;
    }

    public function removeChambre(Chambre $chambre): self
    {
        if ($this->chambre->contains($chambre)) {
            $this->chambre->removeElement($chambre);
        }

        return $this;
    }

    /**
     * @return Collection|OptionService[]
     */
    public function getOptionService(): Collection
    {
        return $this->optionService;
    }

    public function addOptionService(OptionService $optionService): self
    {
        if (!$this->optionService->contains($optionService)) {
            $this->optionService[] = $optionService;
        }

        return $this;
    }

    public function removeOptionService(OptionService $optionService): self
    {
        if ($this->optionService->contains($optionService)) {
            $this->optionService->removeElement($optionService);
        }

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
}
