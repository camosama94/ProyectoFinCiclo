<?php

namespace App\Entity;

use App\Repository\JugadorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JugadorRepository::class)]
class Jugador
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 20)]
    private ?string $posicion = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    private ?string $altura = null;

    #[ORM\Column]
    private ?int $dorsal = null;

    #[ORM\ManyToOne(inversedBy: 'jugadores')]
    private ?Equipo $idEquipo = null;

    /**
     * @var Collection<int, Accion>
     */
    #[ORM\OneToMany(targetEntity: Accion::class, mappedBy: 'idJugador')]
    private Collection $acciones;

    /**
     * @var Collection<int, StatsJugador>
     */
    #[ORM\OneToMany(targetEntity: StatsJugador::class, mappedBy: 'idJugador')]
    private Collection $statsJugador;

    public function __construct()
    {
        $this->acciones = new ArrayCollection();
        $this->statsJugador = new ArrayCollection();
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

    public function getPosicion(): ?string
    {
        return $this->posicion;
    }

    public function setPosicion(string $posicion): static
    {
        $this->posicion = $posicion;

        return $this;
    }

    public function getAltura(): ?string
    {
        return $this->altura;
    }

    public function setAltura(?string $altura): static
    {
        $this->altura = $altura;

        return $this;
    }

    public function getDorsal(): ?int
    {
        return $this->dorsal;
    }

    public function setDorsal(int $dorsal): static
    {
        $this->dorsal = $dorsal;

        return $this;
    }

    public function getIdEquipo(): ?Equipo
    {
        return $this->idEquipo;
    }

    public function setIdEquipo(?Equipo $idEquipo): static
    {
        $this->idEquipo = $idEquipo;

        return $this;
    }

    /**
     * @return Collection<int, Accion>
     */
    public function getAcciones(): Collection
    {
        return $this->acciones;
    }

    public function addAccione(Accion $accione): static
    {
        if (!$this->acciones->contains($accione)) {
            $this->acciones->add($accione);
            $accione->setIdJugador($this);
        }

        return $this;
    }

    public function removeAccione(Accion $accione): static
    {
        if ($this->acciones->removeElement($accione)) {
            // set the owning side to null (unless already changed)
            if ($accione->getIdJugador() === $this) {
                $accione->setIdJugador(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, StatsJugador>
     */
    public function getStatsJugador(): Collection
    {
        return $this->statsJugador;
    }

    public function addStatsJugador(StatsJugador $statsJugador): static
    {
        if (!$this->statsJugador->contains($statsJugador)) {
            $this->statsJugador->add($statsJugador);
            $statsJugador->setIdJugador($this);
        }

        return $this;
    }

    public function removeStatsJugador(StatsJugador $statsJugador): static
    {
        if ($this->statsJugador->removeElement($statsJugador)) {
            // set the owning side to null (unless already changed)
            if ($statsJugador->getIdJugador() === $this) {
                $statsJugador->setIdJugador(null);
            }
        }

        return $this;
    }
}
