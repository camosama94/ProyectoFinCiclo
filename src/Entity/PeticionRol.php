<?php

namespace App\Entity;

use App\Enum\EstadosPeticionesRol;
use Doctrine\DBAL\Types\Types;
use App\Repository\PeticionRolRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PeticionRolRepository::class)]
class PeticionRol
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'peticionRoles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $usuario = null;

    #[ORM\Column(length: 50)]
    private ?string $rol = null;

    #[ORM\Column(type: Types::STRING, enumType: EstadosPeticionesRol::class, length: 20)]
    private EstadosPeticionesRol $status = EstadosPeticionesRol::PENDING;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuario(): ?User
    {
        return $this->usuario;
    }

    public function setUsuario(?User $usuario): static
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getRol(): ?string
    {
        return $this->rol;
    }

    public function setRol(string $rol): static
    {
        $this->rol = $rol;

        return $this;
    }

    public function getStatus(): EstadosPeticionesRol
    {
        return $this->status;
    }

    public function setStatus(EstadosPeticionesRol $status): static
    {
        $this->status = $status;

        return $this;
    }


    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
