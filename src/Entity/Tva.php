<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TvaRepository")
 */
class Tva
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
    private $nom;

    /**
     * @ORM\Column(type="float")
     * @Assert\Type(
     *     type="float",
     *     message="La valeur {{ value }} doit être de type {{ type }}"
     * )
     */
    private $taux;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Chambre", mappedBy="tva")
     */
    private $chambres;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OptionService", mappedBy="tva")
     */
    private $optionServices;

    public function __construct()
    {
        $this->chambres = new ArrayCollection();
        $this->optionServices = new ArrayCollection();
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

    public function getTaux(): ?float
    {
        return $this->taux;
    }

    public function setTaux(float $taux): self
    {
        $this->taux = $taux;

        return $this;
    }

    /**
     * @return Collection|Chambre[]
     */
    public function getChambres(): Collection
    {
        return $this->chambres;
    }

    public function addChambre(Chambre $chambre): self
    {
        if (!$this->chambres->contains($chambre)) {
            $this->chambres[] = $chambre;
            $chambre->setTva($this);
        }

        return $this;
    }

    public function removeChambre(Chambre $chambre): self
    {
        if ($this->chambres->contains($chambre)) {
            $this->chambres->removeElement($chambre);
            // set the owning side to null (unless already changed)
            if ($chambre->getTva() === $this) {
                $chambre->setTva(null);
            }
        }

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
            $optionService->setTva($this);
        }

        return $this;
    }

    public function removeOptionService(OptionService $optionService): self
    {
        if ($this->optionServices->contains($optionService)) {
            $this->optionServices->removeElement($optionService);
            // set the owning side to null (unless already changed)
            if ($optionService->getTva() === $this) {
                $optionService->setTva(null);
            }
        }

        return $this;
    }
}
