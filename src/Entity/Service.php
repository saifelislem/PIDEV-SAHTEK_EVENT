<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
#[ORM\Table(name: 'service')]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Le type est obligatoire.')]
    #[Assert\Choice(
        choices: ['TRANSPORT', 'EQUIPEMENTS'],
        message: 'Choisissez un type valide : TRANSPORT ou EQUIPEMENTS.'
    )]
    private ?string $type = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Length(
        max: 255,
        maxMessage: 'La description ne doit pas dépasser {{ limit }} caractères.'
    )]
    private ?string $description = null;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Assert\PositiveOrZero(message: 'Le coût doit être positif ou égal à zéro.')]
    #[Assert\LessThan(
        value: 1000000,
        message: 'Le coût ne peut excéder 1 000 000.'
    )]
    private ?float $cout = null;

    #[ORM\ManyToOne(targetEntity: Evenement::class, inversedBy: 'services')]
    #[ORM\JoinColumn(name: 'id_evenementAssocie', referencedColumnName: 'id', nullable: false)]
    #[Assert\NotNull(message: 'L\'événement associé est obligatoire.')]
    private ?Evenement $evenement = null;

    #[ORM\OneToMany(mappedBy: 'service', targetEntity: Transport::class)]
    private Collection $transports;

    public function __construct()
    {
        $this->transports = new ArrayCollection();
    }

    // --- Getters and Setters ---

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getCout(): ?float
    {
        return $this->cout;
    }

    public function setCout(?float $cout): self
    {
        $this->cout = $cout;
        return $this;
    }

    public function getEvenement(): ?Evenement
    {
        return $this->evenement;
    }

    public function setEvenement(?Evenement $evenement): self
    {
        $this->evenement = $evenement;
        return $this;
    }

    /**
     * @return Collection<int, Transport>
     */
    public function getTransports(): Collection
    {
        return $this->transports;
    }

    public function addTransport(Transport $transport): self
    {
        if (!$this->transports->contains($transport)) {
            $this->transports->add($transport);
            $transport->setService($this);
        }
        return $this;
    }

    public function removeTransport(Transport $transport): self
    {
        if ($this->transports->removeElement($transport)) {
            if ($transport->getService() === $this) {
                $transport->setService(null);
            }
        }
        return $this;
    }
}
