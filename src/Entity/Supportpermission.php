<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SupportpermissionRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: SupportpermissionRepository::class)]
#[ORM\Table(name: 'supportpermission')]
class Supportpermission
{
    public const PERMISSION_READ = 'READ';
    public const PERMISSION_DOWNLOAD = 'DOWNLOAD';

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
    #[Assert\NotNull(message: "Le support associé ne peut pas être vide.")]
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
    #[Assert\NotBlank(message: "Le type de permission ne peut pas être vide.")]
    #[Assert\Choice(
        choices: [
            self::PERMISSION_READ,
            self::PERMISSION_DOWNLOAD
        ],
        message: "Le type de permission doit être READ ou DOWNLOAD."
    )]
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
    #[Assert\NotBlank(message: "Le rôle ne peut pas être vide.")]
    #[Assert\Length(
        max: 50,
        maxMessage: "Le rôle ne peut pas dépasser {{ limit }} caractères."
    )]
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
    #[Assert\Type("\DateTimeInterface", message: "La date de début doit être une date valide.")]
    private ?\DateTimeInterface $startDate = null;

    // Le constructeur qui initialise la startDate automatiquement
    public function __construct()
    {
        // Initialise la startDate à la date actuelle lors de la création de l'objet
        $this->startDate = new \DateTimeImmutable();
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    // Le setter setStartDate est inutile puisque la date est déjà initialisée dans le constructeur
    public function setStartDate(?\DateTimeInterface $startDate): self
    {
        // Ne rien faire ici pour empêcher la modification manuelle
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: true)]
    #[Assert\Type("\DateTimeInterface", message: "La date de fin doit être une date valide.")]
    #[Assert\GreaterThanOrEqual(
        propertyPath: "startDate",
        message: "La date de fin doit être postérieure ou égale à la date de début."
    )]
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

    #[Assert\Callback]
    public function validateDates(ExecutionContextInterface $context): void
    {
        if ($this->startDate !== null && $this->endDate !== null && $this->startDate > $this->endDate) {
            $context->buildViolation('La date de fin doit être postérieure à la date de début.')
                ->atPath('endDate')
                ->addViolation();
        }
    }
}
