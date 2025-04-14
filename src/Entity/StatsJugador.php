<?php

namespace App\Entity;

use App\Repository\StatsJugadorRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatsJugadorRepository::class)]
class StatsJugador
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $jugando = null;

    #[ORM\Column]
    private ?bool $expulsado = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $puntos = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $tiros2Anot = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $tiros2Int = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $tiros3Anot = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $tiros3Int = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $tiros1Anot = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $tiros1Int = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $reboteOf = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $reboteDef = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $asistencias = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $tapones = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $robos = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $perdidas = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $faltasCom = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $faltasRec = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $masMenos = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $valoracion = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $minutos = null;

    #[ORM\ManyToOne(inversedBy: 'statsJugadores')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Partido $idPartido = null;

    #[ORM\ManyToOne(inversedBy: 'statsJugador')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Jugador $idJugador = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isJugando(): ?bool
    {
        return $this->jugando;
    }

    public function setJugando(bool $jugando): static
    {
        $this->jugando = $jugando;

        return $this;
    }

    public function isExpulsado(): ?bool
    {
        return $this->expulsado;
    }

    public function setExpulsado(bool $expulsado): static
    {
        $this->expulsado = $expulsado;

        return $this;
    }

    public function getPuntos(): ?int
    {
        return $this->puntos;
    }

    public function setPuntos(int $puntos): static
    {
        $this->puntos = $puntos;

        return $this;
    }

    public function getTiros2Anot(): ?int
    {
        return $this->tiros2Anot;
    }

    public function setTiros2Anot(int $tiros2Anot): static
    {
        $this->tiros2Anot = $tiros2Anot;

        return $this;
    }

    public function getTiros2Int(): ?int
    {
        return $this->tiros2Int;
    }

    public function setTiros2Int(int $tiros2Int): static
    {
        $this->tiros2Int = $tiros2Int;

        return $this;
    }

    public function getTiros3Anot(): ?int
    {
        return $this->tiros3Anot;
    }

    public function setTiros3Anot(int $tiros3Anot): static
    {
        $this->tiros3Anot = $tiros3Anot;

        return $this;
    }

    public function getTiros3Int(): ?int
    {
        return $this->tiros3Int;
    }

    public function setTiros3Int(int $tiros3Int): static
    {
        $this->tiros3Int = $tiros3Int;

        return $this;
    }

    public function getTiros1Anot(): ?int
    {
        return $this->tiros1Anot;
    }

    public function setTiros1Anot(int $tiros1Anot): static
    {
        $this->tiros1Anot = $tiros1Anot;

        return $this;
    }

    public function getTiros1Int(): ?int
    {
        return $this->tiros1Int;
    }

    public function setTiros1Int(int $tiros1Int): static
    {
        $this->tiros1Int = $tiros1Int;

        return $this;
    }

    public function getReboteOf(): ?int
    {
        return $this->reboteOf;
    }

    public function setReboteOf(int $reboteOf): static
    {
        $this->reboteOf = $reboteOf;

        return $this;
    }

    public function getReboteDef(): ?int
    {
        return $this->reboteDef;
    }

    public function setReboteDef(int $reboteDef): static
    {
        $this->reboteDef = $reboteDef;

        return $this;
    }

    public function getAsistencias(): ?int
    {
        return $this->asistencias;
    }

    public function setAsistencias(int $asistencias): static
    {
        $this->asistencias = $asistencias;

        return $this;
    }

    public function getTapones(): ?int
    {
        return $this->tapones;
    }

    public function setTapones(int $tapones): static
    {
        $this->tapones = $tapones;

        return $this;
    }

    public function getRobos(): ?int
    {
        return $this->robos;
    }

    public function setRobos(int $robos): static
    {
        $this->robos = $robos;

        return $this;
    }

    public function getPerdidas(): ?int
    {
        return $this->perdidas;
    }

    public function setPerdidas(int $perdidas): static
    {
        $this->perdidas = $perdidas;

        return $this;
    }

    public function getFaltasCom(): ?int
    {
        return $this->faltasCom;
    }

    public function setFaltasCom(int $faltasCom): static
    {
        $this->faltasCom = $faltasCom;

        return $this;
    }

    public function getFaltasRec(): ?int
    {
        return $this->faltasRec;
    }

    public function setFaltasRec(int $faltasRec): static
    {
        $this->faltasRec = $faltasRec;

        return $this;
    }

    public function getMasMenos(): ?int
    {
        return $this->masMenos;
    }

    public function setMasMenos(int $masMenos): static
    {
        $this->masMenos = $masMenos;

        return $this;
    }

    public function getValoracion(): ?int
    {
        return $this->valoracion;
    }

    public function setValoracion(int $valoracion): static
    {
        $this->valoracion = $valoracion;

        return $this;
    }

    public function getMinutos(): ?\DateTimeInterface
    {
        return $this->minutos;
    }

    public function setMinutos(\DateTimeInterface $minutos): static
    {
        $this->minutos = $minutos;

        return $this;
    }

    public function getIdPartido(): ?Partido
    {
        return $this->idPartido;
    }

    public function setIdPartido(?Partido $idPartido): static
    {
        $this->idPartido = $idPartido;

        return $this;
    }

    public function getIdJugador(): ?Jugador
    {
        return $this->idJugador;
    }

    public function setIdJugador(?Jugador $idJugador): static
    {
        $this->idJugador = $idJugador;

        return $this;
    }
}
