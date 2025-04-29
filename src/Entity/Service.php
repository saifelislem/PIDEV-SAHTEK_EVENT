<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;


use App\Repository\ServiceRepository;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
#[ORM\Table(name: 'service')]
class Service
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

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $type = null;

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    #[Assert\Length(
        max: 255,
        maxMessage: 'La description ne doit pas dépasser {{ limit }} caractères'
    )]
    private ?string $description = null;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    #[ORM\Column(type: 'float', nullable: true)]
    #[Assert\PositiveOrZero(message: 'Le coût doit être positif ou zéro')]
      #[Assert\LessThan(
    value: 1000000,
    message: 'Le coût ne peut excéder 1 000 000'
)]
    private ?float $cout = null;

    public function getCout(): ?float
    {
        return $this->cout;
    }

    public function setCout(?float $cout): self
    {
        $this->cout = $cout;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Evenement::class, inversedBy: 'services')]
    #[ORM\JoinColumn(name: 'id_evenementAssocie', referencedColumnName: 'id')]
    private ?Evenement $evenement = null;

    public function getEvenement(): ?Evenement
    {
        return $this->evenement;
    }

    public function setEvenement(?Evenement $evenement): self
    {
        $this->evenement = $evenement;
        return $this;
    }

    #[ORM\OneToMany(targetEntity: Transport::class, mappedBy: 'service')]
    private Collection $transports;

    public function __construct()
    {
        $this->transports = new ArrayCollection();
    }

    /**
     * @return Collection<int, Transport>
     */
    public function getTransports(): Collection
    {
        if (!$this->transports instanceof Collection) {
            $this->transports = new ArrayCollection();
        }
        return $this->transports;
    }

    public function addTransport(Transport $transport): self
    {
        if (!$this->getTransports()->contains($transport)) {
            $this->getTransports()->add($transport);
        }
        return $this;
    }

    public function removeTransport(Transport $transport): self
    {
        $this->getTransports()->removeElement($transport);
        return $this;
    }

}
