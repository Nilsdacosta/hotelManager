<?php

namespace App\Entity;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\OptionService", inversedBy="assignationMenages")
     */
    private $optionService;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Chambre", inversedBy="assignationMenage", cascade={"persist", "remove"})
     */
    private $chambre;

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

    public function getOptionService(): ?OptionService
    {
        return $this->optionService;
    }

    public function setOptionService(?OptionService $optionService): self
    {
        $this->optionService = $optionService;

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
}
