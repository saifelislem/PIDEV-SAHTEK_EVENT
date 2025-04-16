<?php
namespace App\Service;

use App\Entity\Utilisateur;
use App\Entity\Produitsponsoring;
use App\Entity\ContratSponsoring;
use App\Entity\SponsorPending;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SponsorValidationService
{
    private $entityManager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    public function validateSponsor(SponsorPending $sponsorPending): void
    {
        $utilisateur = $this->entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $sponsorPending->getEmail()]);

        if (!$utilisateur) {
            // Nouvelle demande : créer un utilisateur
            $utilisateur = new Utilisateur();
            $utilisateur->setNom($sponsorPending->getNom());
            $utilisateur->setPrenom($sponsorPending->getPrenom());
            $utilisateur->setEmail($sponsorPending->getEmail());
            $utilisateur->setMotDePasse($this->passwordHasher->hashPassword($utilisateur, $sponsorPending->getMotDePasse()));
            $utilisateur->setNationalite($sponsorPending->getNationalite());
            $utilisateur->setGenre($sponsorPending->getGenre());
            $utilisateur->setRole(['ROLE_SPONSOR']);
            $this->entityManager->persist($utilisateur);
        }

        // Créer Produitsponsoring
        $produit = new Produitsponsoring();
        $produit->setNom($sponsorPending->getProduitNom());
        $produit->setDescription($sponsorPending->getProduitDescription());
        $produit->setQuantite($sponsorPending->getProduitQuantite());
        $produit->setPrix($sponsorPending->getProduitPrix());
        $produit->setImage($sponsorPending->getProduitImage());
        $produit->setUtilisateur($utilisateur);

        // Créer ContratSponsoring si nouvelle demande
        if ($sponsorPending->getContratMontant() !== null) {
            $contrat = new ContratSponsoring();
            $contrat->setMontant($sponsorPending->getContratMontant());
            $contrat->setDescription($sponsorPending->getContratDescription());
            $contrat->setUtilisateur($utilisateur);
            $contrat->addProduitsponsoring($produit);
            $this->entityManager->persist($contrat);
        }

        // Persister le produit
        $this->entityManager->persist($produit);

        // Supprimer SponsorPending
        $this->entityManager->remove($sponsorPending);

        // Sauvegarder
        $this->entityManager->flush();
    }
}