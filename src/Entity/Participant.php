<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ParticipantRepository::class)
 * @UniqueEntity(fields={"email"}, message="Il y a deja uhn compte avec cet email")
 */
class Participant implements PasswordAuthenticatedUserInterface, UserInterface
{
    /**
     * var int ID
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string Email of the user <br>
     * Assert Email <br>
     * Assert NotBlank <br>
     * Assert Unique <br>
     * Length-max 180
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="Veuillez renseigner votre Email")
     * @Assert\Email(message="Veuillez renseigner une email valide")
     */
    private $email;

    /**
     * @var string The hashed password <br>
     * Assert Pattern="/((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{8,})$/" (Pa$$w0rd) <br>
     * Assert NotBlank
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Veuillez renseigner votre mot de passe")
     * @Assert\Regex(pattern="/((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{8,})$/", message="Votre mot de passe doit contenir les characteres suivants: 1 Majuscule, 1 minuscule, 1 Charactere spe, 1 chiffre et doit faire 8 ou plus de longeur")
     */
    private $password;

    /**
     * @var string Surname of the user <br>
     * Assert Pattern="/[A-z]{1,50}/" (Nom) <br>
     * Assert NotBlank
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Veuillez renseigner votre nom")
     * @Assert\Regex(pattern="/[A-z]{1,50}/", message="Veuillez renseigner un nom valide")
     */
    private $nom;

    /**
     * @var string Name of the user <br>
     * Assert Pattern="/[A-z]{1,50}/" (Prenom) <br>
     * Assert NotBlank
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Veuillez renseigner votre nom")
     * @Assert\Regex(pattern="/[A-z]{1,50}/", message="Veuillez renseigner un prenom valide")
     */
    private $prenom;

    /**
     * @var string Phone number of the user
     * Assert pattern="/[0-9]{10}/" (012345679)
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\Regex(pattern="/[0-9]{10}/", message="Veuillez renseigner un numero de telephone valide")
     */
    private $telephone;

    /**
     * @var boolean Is user admin
     * @ORM\Column(type="boolean")
     */
    private $administrateur=false;

    /**
     * @var boolean Is user active
     * @ORM\Column(type="boolean")
     */
    private $actif=true;

    /**
     * @var array Sorties auxquelles le user participe
     * @ORM\ManyToMany(targetEntity=Sortie::class, inversedBy="participants", cascade={"persist"})
     */
    private $sortiesParticipants;

    /**
     * @var array Sorties auxquelles le user est Organisateur
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="organisateur", orphanRemoval=true)
     */
    private $sortiesOrganisateur;

    /**
     * @var campus Campus auquel le user est rattaché
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="participants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $campus;

    /**
     * @var string Pseudo du user <br>
     * Assert Unique <br>
     * Length-max 50
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Veuillez renseigner un pseudo")
     */
    private $pseudo;

    public function __construct()
    {
        $this->sortiesParticipants = new ArrayCollection();
        $this->sortiesOrganisateur = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        if($this->administrateur){
            $roles[] = 'ROLE_ADMIN';
        }
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

 /*public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }*/

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $motPasse): self
    {
        $this->password = $motPasse;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function isAdministrateur(): ?bool
    {
        return $this->administrateur;
    }

    public function setAdministrateur(bool $administrateur): self
    {
        $this->administrateur = $administrateur;

        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSortiesParticipants(): Collection
    {
        return $this->sortiesParticipants;
    }

    public function addSortieParticipant(Sortie $sortieParticipant): self
    {
        if (!$this->sortiesParticipants->contains($sortieParticipant)) {
            $this->sortiesParticipants[] = $sortieParticipant;
        }
        return $this;
    }

    public function removeSortieParticipant(Sortie $sortieParticipant): self
    {
        $this->sortiesParticipants->removeElement($sortieParticipant);

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSortiesOrganisateur(): Collection
    {
        return $this->sortiesOrganisateur;
    }

    public function addSortieOrganisateur(Sortie $sortieOrganisateur): self
    {
        if (!$this->sortiesOrganisateur->contains($sortieOrganisateur)) {
            $this->sortiesOrganisateur[] = $sortieOrganisateur;
            $sortieOrganisateur->setOrganisateur($this);
        }

        return $this;
    }

    public function removeSortieOrganisateur(Sortie $sortieOrganisateur): self
    {
        if ($this->sortiesOrganisateur->removeElement($sortieOrganisateur)) {
            // set the owning side to null (unless already changed)
            $sortieOrganisateur->removeParticipant($this);

            }

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }
}
