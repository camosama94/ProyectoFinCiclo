<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var Collection<int, Partido>
     */
    #[ORM\OneToMany(targetEntity: Partido::class, mappedBy: 'idUsuario')]
    private Collection $partidos;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    /**
     * @var Collection<int, PeticionRol>
     */
    #[ORM\OneToMany(targetEntity: PeticionRol::class, mappedBy: 'usuario')]
    private Collection $peticionRoles;

    /**
     * @var Collection<int, Competicion>
     */
    #[ORM\OneToMany(targetEntity: Competicion::class, mappedBy: 'admin')]
    private Collection $competiciones;

    /**
     * @var Collection<int, Competicion>
     */
    #[ORM\ManyToMany(targetEntity: Competicion::class, inversedBy: 'estadistas')]
    private Collection $competicionesEstadista;

    public function __construct()
    {
        $this->partidos = new ArrayCollection();
        $this->peticionRoles = new ArrayCollection();
        $this->competiciones = new ArrayCollection();
        $this->competicionesEstadista = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
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
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Partido>
     */
    public function getPartidos(): Collection
    {
        return $this->partidos;
    }

    public function addPartido(Partido $partido): static
    {
        if (!$this->partidos->contains($partido)) {
            $this->partidos->add($partido);
            $partido->setIdUsuario($this);
        }

        return $this;
    }

    public function removePartido(Partido $partido): static
    {
        if ($this->partidos->removeElement($partido)) {
            // set the owning side to null (unless already changed)
            if ($partido->getIdUsuario() === $this) {
                $partido->setIdUsuario(null);
            }
        }

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return Collection<int, PeticionRol>
     */
    public function getPeticionRol(): Collection
    {
        return $this->peticionRoles;
    }

    public function addPeticionRol(PeticionRol $peticionRole): static
    {
        if (!$this->peticionRoles->contains($peticionRole)) {
            $this->peticionRoles->add($peticionRole);
            $peticionRole->setUsuario($this);
        }

        return $this;
    }

    public function removePeticionRole(PeticionRol $peticionRole): static
    {
        if ($this->peticionRoles->removeElement($peticionRole)) {
            // set the owning side to null (unless already changed)
            if ($peticionRole->getUsuario() === $this) {
                $peticionRole->setUsuario(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Competicion>
     */
    public function getCompeticiones(): Collection
    {
        return $this->competiciones;
    }

    public function addCompeticione(Competicion $competicione): static
    {
        if (!$this->competiciones->contains($competicione)) {
            $this->competiciones->add($competicione);
            $competicione->setAdmin($this);
        }

        return $this;
    }

    public function removeCompeticione(Competicion $competicione): static
    {
        if ($this->competiciones->removeElement($competicione)) {
            // set the owning side to null (unless already changed)
            if ($competicione->getAdmin() === $this) {
                $competicione->setAdmin(null);
            }
        }

        return $this;
    }

    public function getCompeticionesEstadista(): Collection
    {
        return $this->competicionesEstadista;
    }

    public function addCompeticioneEstadista(Competicion $competicion): static
    {
        if (!$this->competicionesEstadista->contains($competicion)) {
            $this->competicionesEstadista->add($competicion);
            $competicion->addEstadista($this);
        }

        return $this;
    }

    public function removeCompeticioneEstadista(Competicion $competicion): static
    {
        if ($this->competicionesEstadista->removeElement($competicion)) {
            $competicion->removeEstadista($this);
        }

        return $this;
    }

}
