<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use App\Repository\SuiviReclamationRepository;

#[ORM\Entity(repositoryClass: SuiviReclamationRepository::class)]
#[ORM\Table(name: 'suivi_reclamation')]
class SuiviReclamation
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

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: "Le statut est obligatoire")]
    #[Assert\Choice(
        choices: ["en_attente", "en_cours", "resolue", "rejetee"],
        message: "Le statut doit être parmi: en_attente, en_cours, resolue ou rejetee")]
    private ?string $status = null;

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: "Le commentaire est obligatoire")]
    #[Assert\Length(
        min: 10,
        max: 1000,
        minMessage: "Le commentaire doit contenir au moins {{ limit }} caractères",
        maxMessage: "Le commentaire ne peut dépasser {{ limit }} caractères"
    )]
    private ?string $commentaire = null;

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): self
    {
        $this->commentaire = $commentaire;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Reclamation::class, inversedBy: 'suiviReclamations')]
    #[ORM\JoinColumn(name: 'id_reclamation', referencedColumnName: 'id')]
    #[Assert\NotNull(message: "La réclamation associée est obligatoire")]

    private ?Reclamation $reclamation = null;

    public function getReclamation(): ?Reclamation
    {
        return $this->reclamation;
    }

    public function setReclamation(?Reclamation $reclamation): self
    {
        $this->reclamation = $reclamation;
        return $this;
    }

}
