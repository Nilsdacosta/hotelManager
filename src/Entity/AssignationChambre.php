<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AssignationChambreRepository")
 */
class AssignationChambre
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\OptionService", inversedBy="assignationChambres")
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
