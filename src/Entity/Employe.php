<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmployeRepository")
 */
class Employe
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
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telephone;

    /**
     * @ORM\Column(type="smallint")
     */
    private $poste;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OptionService", mappedBy="employe")
     */
    private $optionServices;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AssignationMenage", mappedBy="employe")
     */
    private $assignationMenages;

    public function __construct()
    {
        $this->optionServices = new ArrayCollection();
        $this->assignationMenages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getPoste(): ?int
    {
        return $this->poste;
    }

    public function setPoste(int $poste): self
    {
        $this->poste = $poste;

        return $this;
    }

    /**
     * @return Collection|OptionService[]
     */
    public function getOptionServices(): Collection
    {
        return $this->optionServices;
    }

    public function addOptionService(OptionService $optionService): self
    {
        if (!$this->optionServices->contains($optionService)) {
            $this->optionServices[] = $optionService;
            $optionService->setEmploye($this);
        }

        return $this;
    }

    public function removeOptionService(OptionService $optionService): self
    {
        if ($this->optionServices->contains($optionService)) {
            $this->optionServices->removeElement($optionService);
            // set the owning side to null (unless already changed)
            if ($optionService->getEmploye() === $this) {
                $optionService->setEmploye(null);
            }
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
            $assignationMenage->setEmploye($this);
        }

        return $this;
    }

    public function removeAssignationMenage(AssignationMenage $assignationMenage): self
    {
        if ($this->assignationMenages->contains($assignationMenage)) {
            $this->assignationMenages->removeElement($assignationMenage);
            // set the owning side to null (unless already changed)
            if ($assignationMenage->getEmploye() === $this) {
                $assignationMenage->setEmploye(null);
            }
        }

        return $this;
    }
}
