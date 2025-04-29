<?php
namespace App\Entity;

use App\Repository\SponsorPendingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: SponsorPendingRepository::class)]
class SponsorPending
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom est obligatoire")]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: "Le nom doit contenir au moins {{ limit }} caractères",
        maxMessage: "Le nom ne peut dépasser {{ limit }} caractères"
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le prénom est obligatoire")]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: "Le prénom doit contenir au moins {{ limit }} caractères",
        maxMessage: "Le prénom ne peut dépasser {{ limit }} caractères"
    )]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'email est obligatoire")]
    #[Assert\Email(message: "L'email '{{ value }}' n'est pas valide")]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        min: 6,
        minMessage: "Le mot de passe doit contenir au moins {{ limit }} caractères"
    )]
    private ?string $mot_de_passe = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La nationalité est obligatoire")]
    private ?string $nationalite = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le genre est obligatoire")]
    #[Assert\Choice(
        choices: ["homme", "femme", "autre"],
        message: "Le genre doit être homme, femme ou autre"
    )]
    private ?string $genre = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le statut est obligatoire")]
    private ?string $status = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom du produit est obligatoire")]
    #[Assert\Length(max: 255, maxMessage: "Le nom du produit est trop long")]
    private ?string $produit_nom = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "La description du produit est obligatoire")]

    private ?string $produit_description = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "La quantité est obligatoire")]
    #[Assert\Positive(message: "La quantité doit être positive")]
    private ?int $produit_quantite = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le prix est obligatoire")]
    #[Assert\Positive(message: "Le prix doit être positif")]
    private ?float $produit_prix = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\File(
        maxSize: "2M",
        mimeTypes: ["image/jpeg", "image/png", "image/gif"],
        mimeTypesMessage: "Veuillez uploader une image valide (JPEG, PNG ou GIF)"
    )]
    private ?string $produit_image = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    #[Assert\PositiveOrZero(message: "Le montant du contrat doit être positif")]
    private ?string $contrat_montant = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $contrat_description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->mot_de_passe;
    }

    public function setMotDePasse(?string $mot_de_passe): static
    {
        $this->mot_de_passe = $mot_de_passe;
        return $this;
    }

    public function getNationalite(): ?string
    {
        return $this->nationalite;
    }

    public function setNationalite(string $nationalite): static
    {
        $this->nationalite = $nationalite;
        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): static
    {
        $this->genre = $genre;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getProduitNom(): ?string
    {
        return $this->produit_nom;
    }

    public function setProduitNom(string $produit_nom): static
    {
        $this->produit_nom = $produit_nom;
        return $this;
    }

    public function getProduitDescription(): ?string
    {
        return $this->produit_description;
    }

    public function setProduitDescription(string $produit_description): static
    {
        $this->produit_description = $produit_description;
        return $this;
    }

    public function getProduitQuantite(): ?int
    {
        return $this->produit_quantite;
    }

    public function setProduitQuantite(int $produit_quantite): static
    {
        $this->produit_quantite = $produit_quantite;
        return $this;
    }

    public function getProduitPrix(): ?float
    {
        return $this->produit_prix;
    }

    public function setProduitPrix(float $produit_prix): static
    {
        $this->produit_prix = $produit_prix;
        return $this;
    }

    public function getProduitImage(): ?string
    {
        return $this->produit_image;
    }

    public function setProduitImage(?string $produit_image): static
    {
        $this->produit_image = $produit_image;
        return $this;
    }

    public function getContratMontant(): ?string
    {
        return $this->contrat_montant;
    }

    public function setContratMontant(?string $contrat_montant): static
    {
        $this->contrat_montant = $contrat_montant;
        return $this;
    }

    public function getContratDescription(): ?string
    {
        return $this->contrat_description;
    }

    public function setContratDescription(?string $contrat_description): static
    {
        $this->contrat_description = $contrat_description;
        return $this;
    }
}