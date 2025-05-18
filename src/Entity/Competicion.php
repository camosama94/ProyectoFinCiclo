<?php

namespace App\Entity;

use App\Repository\CompeticionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompeticionRepository::class)]
class Competicion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\ManyToOne(inversedBy: 'competiciones')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $admin = null;

    /**
     * @var Collection<int, Equipo>
     */
    #[ORM\OneToMany(targetEntity: Equipo::class, mappedBy: 'competicion')]
    private Collection $equipos;

    /**
     * @var Collection<int, Partido>
     */
    #[ORM\OneToMany(targetEntity: Partido::class, mappedBy: 'competicion')]
    private Collection $partidos;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'competicionesEstadista')]
    private Collection $estadistas;


    public function __construct()
    {
        $this->equipos = new ArrayCollection();
        $this->partidos = new ArrayCollection();
        $this->estadistas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getAdmin(): ?User
    {
        return $this->admin;
    }

    public function setAdmin(?User $admin): static
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * @return Collection<int, Equipo>
     */
    public function getEquipos(): Collection
    {
        return $this->equipos;
    }

    public function addEquipo(Equipo $equipo): static
    {
        if (!$this->equipos->contains($equipo)) {
            $this->equipos->add($equipo);
            $equipo->setCompeticion($this);
        }

        return $this;
    }

    public function removeEquipo(Equipo $equipo): static
    {
        if ($this->equipos->removeElement($equipo)) {
            // set the owning side to null (unless already changed)
            if ($equipo->getCompeticion() === $this) {
                $equipo->setCompeticion(null);
            }
        }

        return $this;
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
            $partido->setCompeticion($this);
        }

        return $this;
    }

    public function removePartido(Partido $partido): static
    {
        if ($this->partidos->removeElement($partido)) {
            // set the owning side to null (unless already changed)
            if ($partido->getCompeticion() === $this) {
                $partido->setCompeticion(null);
            }
        }

        return $this;
    }

    public function getEstadistas(): Collection
    {
        return $this->estadistas;
    }

    public function addEstadista(User $user): static
    {
        if (!$this->estadistas->contains($user)) {
            $this->estadistas->add($user);
            $user->addCompeticioneEstadista($this);
        }

        return $this;
    }

    public function removeEstadista(User $user): static
    {
        if ($this->estadistas->removeElement($user)) {
            $user->removeCompeticioneEstadista($this);
        }

        return $this;
    }

}
