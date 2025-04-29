<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\SupportpermissionRepository;

#[ORM\Entity(repositoryClass: SupportpermissionRepository::class)]
#[ORM\Table(name: 'supportpermission')]
class Supportpermission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Support::class, inversedBy: 'supportpermissions')]
    #[ORM\JoinColumn(name: 'support_id', referencedColumnName: 'id')]
    private ?Support $support = null;

    public function getSupport(): ?Support
    {
        return $this->support;
    }

    public function setSupport(?Support $support): self
    {
        $this->support = $support;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $permission_type = null;

    public function getPermission_type(): ?string
    {
        return $this->permission_type;
    }

    public function setPermission_type(string $permission_type): self
    {
        $this->permission_type = $permission_type;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $role = null;

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $startDate = null;

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $endDate = null;

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function getPermissionType(): ?string
    {
        return $this->permission_type;
    }

    public function setPermissionType(string $permission_type): static
    {
        $this->permission_type = $permission_type;

        return $this;
    }

}
