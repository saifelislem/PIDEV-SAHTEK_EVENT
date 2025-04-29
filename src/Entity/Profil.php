<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProfilRepository;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ProfilRepository::class)]
#[ORM\Table(name: 'profil')]
class Profil
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'profil', targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: 'id_utilisateur', referencedColumnName: 'id', nullable: true)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: "L'email ne peut pas être vide.")]
    #[Assert\Email(message: "L'email fourni n'est pas valide.")]
    private ?string $email = null;

    #[ORM\Column(type: 'string', nullable: true)]
    #[Assert\Length(max: 15, maxMessage: "Le numéro de téléphone ne peut pas dépasser {{ limit }} caractères.")]
    #[Assert\Regex(pattern: "/^\+?\d{10,15}$/", message: "Le numéro de téléphone n'est pas valide.")]
    private ?string $num_tel = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    #[Assert\NotNull(message: "Le choix pour la newsletter doit être défini.")] // Nouveau champ
    private ?bool $newsletter = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

   
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getNumTel(): ?string
    {
        return $this->num_tel;
    }

    public function setNumTel(?string $num_tel): self
    {
        $this->num_tel = $num_tel;
        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getNewsletter(): ?bool
    {
        return $this->newsletter;
    }

    public function setNewsletter(?bool $newsletter): self
    {
        $this->newsletter = $newsletter;
        return $this;
    }
}