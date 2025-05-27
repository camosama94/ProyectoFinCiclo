<?php

namespace App\Entity;

use App\Repository\PartidoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PartidoRepository::class)]
class Partido
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $localizacion = null;

    #[ORM\ManyToOne(inversedBy: 'partidosLocal')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Equipo $idEquipoLocal = null;

    #[ORM\Column]
    private ?int $puntosLocal = 0;

    #[ORM\ManyToOne(inversedBy: 'partidosVisitante')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Equipo $idEquipoVisitante = null;

    #[ORM\Column]
    private ?int $puntosVisitante = 0;

    #[ORM\Column]
    private ?bool $finalizado = false;

    /**
     * @var Collection<int, Accion>
     */
    #[ORM\OneToMany(targetEntity: Accion::class, mappedBy: 'idPartido')]
    private Collection $acciones;

    /**
     * @var Collection<int, StatsJugador>
     */
    #[ORM\OneToMany(targetEntity: StatsJugador::class, mappedBy: 'idPartido')]
    private Collection $statsJugadores;

    #[ORM\ManyToOne(inversedBy: 'partidos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $idUsuario = null;

    #[ORM\ManyToOne(inversedBy: 'partidos')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Competicion $competicion = null;

    public function __construct()
    {
        $this->acciones = new ArrayCollection();
        $this->statsJugadores = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): static
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getLocalizacion(): ?string
    {
        return $this->localizacion;
    }

    public function setLocalizacion(?string $localizacion): static
    {
        $this->localizacion = $localizacion;

        return $this;
    }

    public function isFinalizado(): bool
    {
        return $this->finalizado ?? false;
    }

    public function setFinalizado(bool $finalizado): self
    {
        $this->finalizado = $finalizado;

        return $this;
    }


    public function getIdEquipoLocal(): ?Equipo
    {
        return $this->idEquipoLocal;
    }

    public function setIdEquipoLocal(?Equipo $idEquipoLocal): static
    {
        $this->idEquipoLocal = $idEquipoLocal;

        return $this;
    }

    public function getPuntosLocal(): int
    {
        return $this->puntosLocal;
    }

    public function setPuntosLocal(int $puntosLocal): self
    {
        $this->puntosLocal = $puntosLocal;
        return $this;
    }


    public function getIdEquipoVisitante(): ?Equipo
    {
        return $this->idEquipoVisitante;
    }

    public function setIdEquipoVisitante(?Equipo $idEquipoVisitante): static
    {
        $this->idEquipoVisitante = $idEquipoVisitante;

        return $this;
    }

    public function getPuntosVisitante(): int
    {
        return $this->puntosVisitante;
    }

    public function setPuntosVisitante(int $puntosVisitante): self
    {
        $this->puntosVisitante = $puntosVisitante;
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
            $accione->setIdPartido($this);
        }

        return $this;
    }

    public function removeAccione(Accion $accione): static
    {
        if ($this->acciones->removeElement($accione)) {
            // set the owning side to null (unless already changed)
            if ($accione->getIdPartido() === $this) {
                $accione->setIdPartido(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, StatsJugador>
     */
    public function getStatsJugadores(): Collection
    {
        return $this->statsJugadores;
    }

    public function addStatsJugadore(StatsJugador $statsJugadore): static
    {
        if (!$this->statsJugadores->contains($statsJugadore)) {
            $this->statsJugadores->add($statsJugadore);
            $statsJugadore->setIdPartido($this);
        }

        return $this;
    }

    public function removeStatsJugadore(StatsJugador $statsJugadore): static
    {
        if ($this->statsJugadores->removeElement($statsJugadore)) {
            // set the owning side to null (unless already changed)
            if ($statsJugadore->getIdPartido() === $this) {
                $statsJugadore->setIdPartido(null);
            }
        }

        return $this;
    }

    public function getIdUsuario(): ?User
    {
        return $this->idUsuario;
    }

    public function setIdUsuario(?User $idUsuario): static
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }

    public function getCompeticion(): ?Competicion
    {
        return $this->competicion;
    }

    public function setCompeticion(?Competicion $competicion): static
    {
        $this->competicion = $competicion;

        return $this;
    }

    public function isActivoPorTiempo(): bool
    {
        $ahora = new \DateTime();
        $mediaHoraAntes = (clone $this->fecha)->modify('-30 minutes');

        return $ahora >= $mediaHoraAntes && !$this->isFinalizado();
    }

}
