<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\TransportRepository;

#[ORM\Entity(repositoryClass: TransportRepository::class)]
#[ORM\Table(name: 'transport')]
class Transport
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

    #[ORM\Column(type: 'date', nullable: true)]
    #[Assert\NotNull(message: "La date est obligatoire")]
    #[Assert\GreaterThanOrEqual(
        "today",
        message: "La date ne peut pas être dans le passé"
    )]
    private ?\DateTimeInterface $date = null;

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    #[ORM\Column(type: 'time', nullable: true)]
    #[Assert\NotNull(message: "L'heure de départ est obligatoire")]
    private ?\DateTime $heureDepart = null;
    
    public function getHeureDepart(): ?\DateTime
    {
        return $this->heureDepart;
    }
    
    public function setHeureDepart(?\DateTime $heureDepart): self
    {
        $this->heureDepart = $heureDepart;
        return $this;
    }
    

 



    #[ORM\Column(type: 'string', nullable: true)]
    #[Assert\NotBlank(message: "Le point de départ est obligatoire")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le point de départ ne peut dépasser {{ limit }} caractères"
    )]
    private ?string $pointDepart = null;

    public function getPointDepart(): ?string
    {
        return $this->pointDepart;
    }

    public function setPointDepart(?string $pointDepart): self
    {
        $this->pointDepart = $pointDepart;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    #[Assert\NotBlank(message: "La destination est obligatoire")]
    #[Assert\Length(
        max: 255,
        maxMessage: "La destination ne peut dépasser {{ limit }} caractères"
    )]
    private ?string $destination = null;

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(?string $destination): self
    {
        $this->destination = $destination;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    #[Assert\NotBlank(message: "Le véhicule est obligatoire")]
    #[Assert\Choice(
        choices: ["voiture", "bus", "avion", "train", "bateau"],
        message: "Le véhicule doit être parmi: voiture, bus, avion, train ou bateau"
    )]
    private ?string $vehicule = null;

    public function getVehicule(): ?string
    {
        return $this->vehicule;
    }

    public function setVehicule(?string $vehicule): self
    {
        $this->vehicule = $vehicule;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Evenement::class, inversedBy: 'transports')]
    #[ORM\JoinColumn(name: 'id_evenementAssocie', referencedColumnName: 'id')]
    #[Assert\NotNull(message: "L'événement associé est obligatoire")]
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

    #[ORM\ManyToOne(targetEntity: Service::class, inversedBy: 'transports')]
    #[ORM\JoinColumn(name: 'id_service', referencedColumnName: 'id')]
    #[Assert\NotNull(message: "Le service associé est obligatoire")]
    private ?Service $service = null;

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;
        return $this;
    }

    // Exemple de méthode pour obtenir la date sous forme de chaîne formatée
    public function getFormattedDate(): ?string
    {
        if ($this->date instanceof \DateTimeInterface) {
            return $this->date->format('Y-m-d');  // Formater la date au format 'YYYY-MM-DD'
        }
        return null;
    }
}
