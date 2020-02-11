<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\EmployeRepository")
 */
class Employe implements UserInterface
{
    const ROLE_1 = 'ROLE_SUPER_ADMIN';
    const ROLE_2 = 'ROLE_ADMIN';
    const ROLE_3 = 'ROLE_USER';
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
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *      message="La valeur ne peut être vide")
     */
    private $username;
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *      message="La valeur ne peut être vide")
     */
    private $prenom;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(
     *      message="La valeur ne peut être vide")
     */
    private $telephone;
    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];
    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;
    /**
     * @ORM\Column(type="smallint")
     * @Assert\Choice({1, 2, 3, 4}, message="Choississez un poste valide.")
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
    public function getRenderPoste(): ?string
    {
        if ($this->poste == 2){
            return "Réceptionniste";
        }elseif($this->poste == 3){
            return "Gourvernante";
        }elseif($this->poste == 1){
            return "Directeur";
        }elseif($this->poste == 5){
            return "Stagiaire";   
        }else{
            return "Femme de chambre";
        }
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
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }
    /**
     * @see UserInterface
     */
    public function getRoles()
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }
    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }
    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }
    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
    /**
     * Set the value of username
     *
     * @return  self
     */ 
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }
}
