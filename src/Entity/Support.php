<?php

namespace App\Entity;

use App\Repository\SupportRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SupportRepository::class)]
#[ORM\Table(name: 'support')]
class Support
{
    public const TYPE_DOCUMENT = 'DOCUMENT';
    public const TYPE_PPT = 'PPT';
    public const TYPE_VIDEO = 'VIDEO';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $url = null;

    #[ORM\Column(type: 'string', nullable: true)]
    #[Assert\Choice(
        choices: [self::TYPE_DOCUMENT, self::TYPE_PPT, self::TYPE_VIDEO],
        message: 'Le type doit être DOCUMENT, PPT ou VIDEO.'
    )]
    private ?string $type = null;

    #[ORM\ManyToOne(targetEntity: Evenement::class)]
    #[ORM\JoinColumn(name: 'id_evenement_associe', referencedColumnName: 'id', nullable: true)]
    #[Assert\NotNull(message: 'L\'événement associé est obligatoire!')]
    private ?Evenement $evenement = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Le titre est obligatoire!')]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'Le titre doit contenir au moins {{ limit }} caractères!',
        maxMessage: 'Le titre ne peut pas dépasser {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^[\p{L}0-9\s\-\'\,\.\:\!\?]+$/u',
        message: 'Le titre contient des caractères non autorisés.'
    )]
    private ?string $titre = null;

    #[ORM\OneToMany(
        targetEntity: Supportpermission::class, 
        mappedBy: 'support',
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $supportpermissions;

    public function __construct()
    {
        $this->supportpermissions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;
        return $this;
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

    public function getEvenement(): ?Evenement
    {
        return $this->evenement;
    }

    public function setEvenement(?Evenement $evenement): self
    {
        $this->evenement = $evenement;
        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;
        return $this;
    }

    /**
     * @return Collection<int, Supportpermission>
     */
    public function getSupportpermissions(): Collection
    {
        return $this->supportpermissions;
    }

    public function addSupportpermission(Supportpermission $supportpermission): self
    {
        if (!$this->supportpermissions->contains($supportpermission)) {
            $this->supportpermissions->add($supportpermission);
            $supportpermission->setSupport($this);
        }
        return $this;
    }

    public function removeSupportpermission(Supportpermission $supportpermission): self
    {
        if ($this->supportpermissions->removeElement($supportpermission)) {
            if ($supportpermission->getSupport() === $this) {
                $supportpermission->setSupport(null);
            }
        }
        return $this;
    }

    public function __toString(): string
    {
        return $this->titre ?? '';
    }
}