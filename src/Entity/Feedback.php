<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\FeedbackRepository;

#[ORM\Entity(repositoryClass: FeedbackRepository::class)]
#[ORM\Table(name: 'feedback')]
class Feedback
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank(message: "Le mot de passe est obligatoire.")]
    #[Assert\Length(min: 4, max: 255, minMessage: "Le mot de passe doit contenir au moins {{ limit }} caractères.", maxMessage: "Le mot de passe ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $pass = null;

    #[ORM\Column(type: 'string', length: 1000, nullable: false)]
    #[Assert\NotBlank(message: "Le message est obligatoire.")]
    #[Assert\Length(min: 10, max: 1000, minMessage: "Le message doit contenir au moins {{ limit }} caractères.", maxMessage: "Le message ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $message = null;

    #[ORM\Column(type: 'integer', nullable: false)]
    #[Assert\NotBlank(message: "La note est obligatoire.")]
    #[Assert\Range(min: 1, max: 5, notInRangeMessage: "La note doit être comprise entre {{ min }} et {{ max }}.")]
    private ?int $note = null;

    // Getters et Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getPass(): ?string
    {
        return $this->pass;
    }

    public function setPass(string $pass): self
    {
        $this->pass = $pass;
        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;
        return $this;
    }
}