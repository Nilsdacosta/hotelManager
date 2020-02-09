<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AssignationMenageRepository")
 */
class AssignationMenage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Employe", inversedBy="assignationMenages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $employe;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Chambre", inversedBy="assignationMenages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $chambre;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\OptionService", inversedBy="assignationMenages")
     */
    private $optionService;

    public function __construct()
    {
        $this->optionService = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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

    public function getChambre(): ?Chambre
    {
        return $this->chambre;
    }

    public function setChambre(?Chambre $chambre): self
    {
        $this->chambre = $chambre;

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
}
