<?php

namespace App\Entity;

use App\Enum\TipoAccion;
use App\Repository\AccionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccionRepository::class)]
class Accion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: TipoAccion::class)]
    private TipoAccion $tipoDeAccion;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $minuto = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $valor = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $descripcion = null;

    #[ORM\ManyToOne(inversedBy: 'acciones')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Partido $idPartido = null;

    #[ORM\ManyToOne(inversedBy: 'acciones')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Jugador $idJugador = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipoDeAccion(): TipoAccion
    {
        return $this->tipoDeAccion;
    }

    public function setTipoDeAccion(TipoAccion $tipoDeAccion): static
    {
        $this->tipoDeAccion = $tipoDeAccion;

        return $this;
    }


    public function getMinuto(): ?\DateTimeInterface
    {
        return $this->minuto;
    }

    public function setMinuto(\DateTimeInterface $minuto): static
    {
        $this->minuto = $minuto;

        return $this;
    }

    public function getValor(): ?int
    {
        return $this->valor;
    }

    public function setValor(int $valor): static
    {
        $this->valor = $valor;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): static
    {
        $this->descripcion = $descripcion;

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
