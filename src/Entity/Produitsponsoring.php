<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\ProduitsponsoringRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProduitsponsoringRepository::class)]
#[ORM\Table(name: 'produitsponsoring')]
class Produitsponsoring
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: "Le nom du produit est obligatoire.")]
    #[Assert\Length(max: 255, maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $nom = null;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: "La description est obligatoire.")]
    #[Assert\Length(min: 10, minMessage: "La description doit contenir au moins {{ limit }} caractères.")]
    private ?string $description = null;

    #[ORM\Column(type: 'integer', nullable: false)]
    #[Assert\NotBlank(message: "La quantité est obligatoire.")]
    #[Assert\Positive(message: "La quantité doit être positive.")]
    private ?int $quantite = null;

    #[ORM\Column(type: 'float', nullable: false)]
    #[Assert\NotBlank(message: "Le prix est obligatoire.")]
    #[Assert\Positive(message: "Le prix doit être positif.")]
    private ?float $prix = null;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\Length(max: 255, maxMessage: "Le chemin de l'image ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $image = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'produitsponsorings')]
    #[ORM\JoinColumn(name: 'id_utilisateur', referencedColumnName: 'id')]
    #[Assert\NotBlank(message: "L'utilisateur est obligatoire.")]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToMany(targetEntity: ContratSponsoring::class, mappedBy: 'produitsponsorings')]
    private Collection $contratSponsorings;

    public function __construct()
    {
        $this->contratSponsorings = new ArrayCollection();
    }

    // Getters et Setters (inchangés sauf pour le typage strict)
    public function getId(): ?int { return $this->id; }
    public function setId(int $id): self { $this->id = $id; return $this; }

    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): self { $this->nom = $nom; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $description): self { $this->description = $description; return $this; }

    public function getQuantite(): ?int { return $this->quantite; }
    public function setQuantite(int $quantite): self { $this->quantite = $quantite; return $this; }

    public function getPrix(): ?float { return $this->prix; }
    public function setPrix(float $prix): self { $this->prix = $prix; return $this; }

    public function getImage(): ?string { return $this->image; }
    public function setImage(string $image): self { $this->image = $image; return $this; }

    public function getUtilisateur(): ?Utilisateur { return $this->utilisateur; }
    public function setUtilisateur(?Utilisateur $utilisateur): self { $this->utilisateur = $utilisateur; return $this; }

    /**
     * @return Collection<int, ContratSponsoring>
     */
    public function getContratSponsorings(): Collection { return $this->contratSponsorings; }
    public function addContratSponsoring(ContratSponsoring $contratSponsoring): self
    {
        if (!$this->contratSponsorings->contains($contratSponsoring)) {
            $this->contratSponsorings->add($contratSponsoring);
        }
        return $this;
    }
    public function removeContratSponsoring(ContratSponsoring $contratSponsoring): self
    {
        $this->contratSponsorings->removeElement($contratSponsoring);
        return $this;
    }
}