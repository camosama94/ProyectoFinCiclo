<?php

namespace App\Entity;

use App\Repository\EquipoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipoRepository::class)]
class Equipo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    private ?string $nombre = null;

    #[ORM\Column(length: 100)]
    private ?string $entrenador = null;

    /**
     * @var Collection<int, Jugador>
     */
    #[ORM\OneToMany(targetEntity: Jugador::class, mappedBy: 'idEquipo')]
    private Collection $jugadores;

    /**
     * @var Collection<int, Partido>
     */
    #[ORM\OneToMany(targetEntity: Partido::class, mappedBy: 'idEquipoLocal')]
    private Collection $partidosLocal;

    /**
     * @var Collection<int, Partido>
     */
    #[ORM\OneToMany(targetEntity: Partido::class, mappedBy: 'idEquipoVisitante')]
    private Collection $partidosVisitante;

    public function __construct()
    {
        $this->jugadores = new ArrayCollection();
        $this->partidosLocal = new ArrayCollection();
        $this->partidosVisitante = new ArrayCollection();
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

    public function getEntrenador(): ?string
    {
        return $this->entrenador;
    }

    public function setEntrenador(string $entrenador): static
    {
        $this->entrenador = $entrenador;

        return $this;
    }

    /**
     * @return Collection<int, Jugador>
     */
    public function getJugadores(): Collection
    {
        return $this->jugadores;
    }

    public function addJugadore(Jugador $jugadore): static
    {
        if (!$this->jugadores->contains($jugadore)) {
            $this->jugadores->add($jugadore);
            $jugadore->setIdEquipo($this);
        }

        return $this;
    }

    public function removeJugadore(Jugador $jugadore): static
    {
        if ($this->jugadores->removeElement($jugadore)) {
            // set the owning side to null (unless already changed)
            if ($jugadore->getIdEquipo() === $this) {
                $jugadore->setIdEquipo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Partido>
     */
    public function getPartidosLocal(): Collection
    {
        return $this->partidosLocal;
    }

    public function addPartidosLocal(Partido $partidosLocal): static
    {
        if (!$this->partidosLocal->contains($partidosLocal)) {
            $this->partidosLocal->add($partidosLocal);
            $partidosLocal->setIdEquipoLocal($this);
        }

        return $this;
    }

    public function removePartidosLocal(Partido $partidosLocal): static
    {
        if ($this->partidosLocal->removeElement($partidosLocal)) {
            // set the owning side to null (unless already changed)
            if ($partidosLocal->getIdEquipoLocal() === $this) {
                $partidosLocal->setIdEquipoLocal(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Partido>
     */
    public function getPartidosVisitante(): Collection
    {
        return $this->partidosVisitante;
    }

    public function addPartidosVisitante(Partido $partidosVisitante): static
    {
        if (!$this->partidosVisitante->contains($partidosVisitante)) {
            $this->partidosVisitante->add($partidosVisitante);
            $partidosVisitante->setIdEquipoVisitante($this);
        }

        return $this;
    }

    public function removePartidosVisitante(Partido $partidosVisitante): static
    {
        if ($this->partidosVisitante->removeElement($partidosVisitante)) {
            // set the owning side to null (unless already changed)
            if ($partidosVisitante->getIdEquipoVisitante() === $this) {
                $partidosVisitante->setIdEquipoVisitante(null);
            }
        }

        return $this;
    }
}
