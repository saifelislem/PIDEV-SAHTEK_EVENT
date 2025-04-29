<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\UtilisateurRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\Table(name: 'utilisateur')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private ?string $nom = null;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private ?string $prenom = null;

    #[ORM\Column(type: 'json', nullable: false)]
    private array $role = ['ROLE_USER'];

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private ?string $mot_de_passe = null;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private ?string $nationalite = null;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private ?string $genre = null;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private ?string $email = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $permission = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $statut = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $verification_token = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $is_verified = null;

    #[ORM\OneToMany(targetEntity: ContratSponsoring::class, mappedBy: 'utilisateur')]
    private Collection $contratSponsorings;

    #[ORM\OneToMany(targetEntity: Evenement::class, mappedBy: 'utilisateur')]
    private Collection $evenements;

    #[ORM\OneToMany(targetEntity: Participation::class, mappedBy: 'utilisateur')]
    private Collection $participations;

    #[ORM\OneToMany(targetEntity: Produitsponsoring::class, mappedBy: 'utilisateur')]
    private Collection $produitsponsorings;

    #[ORM\OneToMany(targetEntity: Reclamation::class, mappedBy: 'utilisateur')]
    private Collection $reclamations;

    #[ORM\OneToOne(mappedBy: 'utilisateur', targetEntity: Profil::class, cascade: ['persist', 'remove'])]
    private ?Profil $profil = null;

    public function __construct()
    {
        $this->contratSponsorings = new ArrayCollection();
        $this->evenements = new ArrayCollection();
        $this->participations = new ArrayCollection();
        $this->produitsponsorings = new ArrayCollection();
        $this->reclamations = new ArrayCollection();
    }

    public function getRoles(): array
    {
        $roles = $this->role;
        // Garantir au moins ROLE_USER
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }
        return array_unique($roles);
    }

    public function getPassword(): ?string
    {
        return $this->mot_de_passe;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function eraseCredentials(): void
    {
        // Rien à effacer ici, mais méthode requise par l'interface
    }

    public function getSalt(): ?string
    {
        return null; // Pas besoin de sel avec les hashers modernes
    }

    // Getters and setters
    public function getId(): ?int { return $this->id; }
    public function setNom(string $nom): self { $this->nom = $nom; return $this; }
    public function getNom(): ?string { return $this->nom; }
    public function setPrenom(string $prenom): self { $this->prenom = $prenom; return $this; }
    public function getPrenom(): ?string { return $this->prenom; }
    public function setRole(array $role): self { $this->role = $role; return $this; }
    public function getRole(): array { return $this->role; }
    public function setMotDePasse(string $mot_de_passe): self { $this->mot_de_passe = $mot_de_passe; return $this; }
    public function setNationalite(string $nationalite): self { $this->nationalite = $nationalite; return $this; }
    public function getNationalite(): ?string { return $this->nationalite; }
    public function setGenre(string $genre): self { $this->genre = $genre; return $this; }
    public function getGenre(): ?string { return $this->genre; }
    public function setEmail(string $email): self { $this->email = $email; return $this; }
    public function getEmail(): ?string { return $this->email; }
    public function setPermission(?bool $permission): self { $this->permission = $permission; return $this; }
    public function getPermission(): ?bool { return $this->permission; }
    public function setStatut(?bool $statut): self { $this->statut = $statut; return $this; }
    public function getStatut(): ?bool { return $this->statut; }
    public function setVerificationToken(?string $verification_token): self { $this->verification_token = $verification_token; return $this; }
    public function getVerificationToken(): ?string { return $this->verification_token; }
    public function setIsVerified(?bool $is_verified): self { $this->is_verified = $is_verified; return $this; }
    public function isVerified(): ?bool { return $this->is_verified; }

    // Relationships
    public function getContratSponsorings(): Collection { return $this->contratSponsorings; }
    public function addContratSponsoring(ContratSponsoring $contratSponsoring): self { if (!$this->contratSponsorings->contains($contratSponsoring)) { $this->contratSponsorings->add($contratSponsoring); } return $this; }
    public function removeContratSponsoring(ContratSponsoring $contratSponsoring): self { $this->contratSponsorings->removeElement($contratSponsoring); return $this; }
    public function getEvenements(): Collection { return $this->evenements; }
    public function addEvenement(Evenement $evenement): self { if (!$this->evenements->contains($evenement)) { $this->evenements->add($evenement); } return $this; }
    public function removeEvenement(Evenement $evenement): self { $this->evenements->removeElement($evenement); return $this; }
    public function getParticipations(): Collection { return $this->participations; }
    public function addParticipation(Participation $participation): self { if (!$this->participations->contains($participation)) { $this->participations->add($participation); } return $this; }
    public function removeParticipation(Participation $participation): self { $this->participations->removeElement($participation); return $this; }
    public function getProduitsponsorings(): Collection { return $this->produitsponsorings; }
    public function addProduitsponsoring(Produitsponsoring $produitsponsoring): self { if (!$this->produitsponsorings->contains($produitsponsoring)) { $this->produitsponsorings->add($produitsponsoring); } return $this; }
    public function removeProduitsponsoring(Produitsponsoring $produitsponsoring): self { $this->produitsponsorings->removeElement($produitsponsoring); return $this; }
    public function getReclamations(): Collection { return $this->reclamations; }
    public function addReclamation(Reclamation $reclamation): self { if (!$this->reclamations->contains($reclamation)) { $this->reclamations->add($reclamation); } return $this; }
    public function removeReclamation(Reclamation $reclamation): self { $this->reclamations->removeElement($reclamation); return $this; }

    // Profil relationship
    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;
        if ($profil && $profil->getUtilisateur() !== $this) {
            $profil->setUtilisateur($this);
        }
        return $this;
    }
}