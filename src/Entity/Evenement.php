<?php
namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\EvenementRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
#[ORM\Table(name: 'evenement')]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: "Le nom de l'événement est obligatoire.")]
    private ?string $nom = null;

    #[ORM\Column(type: 'text', nullable: false)]
    #[Assert\NotBlank(message: "La description est obligatoire.")]
    #[Assert\Length(min: 10, minMessage: "La description doit contenir au moins {{ limit }} caractères.")]
    private ?string $description = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    #[Assert\NotBlank(message: "La date est obligatoire.")]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: "Le lieu est obligatoire.")]
    private ?string $lieu = null;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: "Le statut est obligatoire.")]
    private ?string $statut = null;

    #[ORM\Column(type: 'integer', nullable: false)]
    #[Assert\NotBlank(message: "La capacité maximale est obligatoire.")]
    #[Assert\Positive(message: "La capacité maximale doit être positive.")]
    private ?int $capacite_max = null;

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $image = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'evenements')]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id')]
    private ?Utilisateur $utilisateur = null;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: "Le type est obligatoire.")]
    private ?string $type = null;

    #[ORM\OneToMany(targetEntity: ContratSponsoring::class, mappedBy: 'evenement')]
    private Collection $contratSponsorings;

    #[ORM\OneToMany(targetEntity: Participation::class, mappedBy: 'evenement')]
    private Collection $participations;

    #[ORM\OneToMany(targetEntity: Reclamation::class, mappedBy: 'evenement')]
    private Collection $reclamations;

    #[ORM\OneToMany(targetEntity: Service::class, mappedBy: 'evenement')]
    private Collection $services;

    #[ORM\OneToMany(targetEntity: Transport::class, mappedBy: 'evenement')]
    private Collection $transports;

    public function __construct()
    {
        $this->contratSponsorings = new ArrayCollection();
        $this->participations = new ArrayCollection();
        $this->reclamations = new ArrayCollection();
        $this->services = new ArrayCollection();
        $this->transports = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): self { $this->id = $id; return $this; }

    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): self { $this->nom = $nom; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $description): self { $this->description = $description; return $this; }

    public function getDate(): ?\DateTimeImmutable { return $this->date; }
    public function setDate(\DateTimeImmutable $date): self { $this->date = $date; return $this; }

    public function getLieu(): ?string { return $this->lieu; }
    public function setLieu(string $lieu): self { $this->lieu = $lieu; return $this; }

    public function getStatut(): ?string { return $this->statut; }
    public function setStatut(string $statut): self { $this->statut = $statut; return $this; }

    public function getCapaciteMax(): ?int { return $this->capacite_max; }
    public function setCapaciteMax(int $capacite_max): self { $this->capacite_max = $capacite_max; return $this; }

    public function getImage(): ?string { return $this->image; }
    public function setImage(string $image): self { $this->image = $image; return $this; }

    public function getUtilisateur(): ?Utilisateur { return $this->utilisateur; }
    public function setUtilisateur(?Utilisateur $utilisateur): self { $this->utilisateur = $utilisateur; return $this; }

    public function getType(): ?string { return $this->type; }
    public function setType(string $type): self { $this->type = $type; return $this; }

    public function getContratSponsorings(): Collection { return $this->contratSponsorings ?: new ArrayCollection(); }
    public function addContratSponsoring(ContratSponsoring $contratSponsoring): self { if (!$this->getContratSponsorings()->contains($contratSponsoring)) { $this->getContratSponsorings()->add($contratSponsoring); } return $this; }
    public function removeContratSponsoring(ContratSponsoring $contratSponsoring): self { $this->getContratSponsorings()->removeElement($contratSponsoring); return $this; }

    public function getParticipations(): Collection { return $this->participations ?: new ArrayCollection(); }
    public function addParticipation(Participation $participation): self { if (!$this->getParticipations()->contains($participation)) { $this->getParticipations()->add($participation); } return $this; }
    public function removeParticipation(Participation $participation): self { $this->getParticipations()->removeElement($participation); return $this; }

    public function getReclamations(): Collection { return $this->reclamations ?: new ArrayCollection(); }
    public function addReclamation(Reclamation $reclamation): self { if (!$this->getReclamations()->contains($reclamation)) { $this->getReclamations()->add($reclamation); } return $this; }
    public function removeReclamation(Reclamation $reclamation): self { $this->getReclamations()->removeElement($reclamation); return $this; }

    public function getServices(): Collection { return $this->services ?: new ArrayCollection(); }
    public function addService(Service $service): self { if (!$this->getServices()->contains($service)) { $this->getServices()->add($service); } return $this; }
    public function removeService(Service $service): self { $this->getServices()->removeElement($service); return $this; }

    public function getTransports(): Collection { return $this->transports ?: new ArrayCollection(); }
    public function addTransport(Transport $transport): self { if (!$this->getTransports()->contains($transport)) { $this->getTransports()->add($transport); } return $this; }
    public function removeTransport(Transport $transport): self { $this->getTransports()->removeElement($transport); return $this; }
}