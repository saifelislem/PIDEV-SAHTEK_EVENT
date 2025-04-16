<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Corrected to handle all foreign key updates safely.
 */
final class Version20250404103057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update schema to use User table and fix foreign key constraints';
    }

    public function up(Schema $schema): void
    {
        // Create User table if not exists
        $this->addSql('CREATE TABLE IF NOT EXISTS User (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, nationalite VARCHAR(255) NOT NULL, genre VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, permission TINYINT(1) DEFAULT NULL, statut TINYINT(1) DEFAULT NULL, verification_token VARCHAR(255) DEFAULT NULL, is_verified TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Drop old utilisateur if it exists
        $this->addSql('DROP TABLE IF EXISTS utilisateur');

        // Update foreign keys with safe DROP and ADD
        $this->addSql('ALTER TABLE contrat_sponsoring DROP FOREIGN KEY IF EXISTS FK_596F506550EAE44');
        $this->addSql('ALTER TABLE contrat_sponsoring ADD CONSTRAINT FK_596F506550EAE44 FOREIGN KEY (id_utilisateur) REFERENCES User (id) ON DELETE SET NULL');

        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY IF EXISTS FK_B26681E6B3CA4B');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681E6B3CA4B FOREIGN KEY (id_user) REFERENCES User (id) ON DELETE SET NULL');

        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY IF EXISTS FK_AB55E24F6B3CA4B');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F6B3CA4B FOREIGN KEY (id_user) REFERENCES User (id) ON DELETE SET NULL');

        $this->addSql('ALTER TABLE produitsponsoring DROP FOREIGN KEY IF EXISTS FK_2D525AFF50EAE44');
        $this->addSql('ALTER TABLE produitsponsoring ADD CONSTRAINT FK_2D525AFF50EAE44 FOREIGN KEY (id_utilisateur) REFERENCES User (id) ON DELETE SET NULL');

        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY IF EXISTS FK_CE6064046B3CA4B');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064046B3CA4B FOREIGN KEY (id_user) REFERENCES User (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // Revert changes safely
        $this->addSql('ALTER TABLE contrat_sponsoring DROP FOREIGN KEY IF EXISTS FK_596F506550EAE44');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY IF EXISTS FK_B26681E6B3CA4B');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY IF EXISTS FK_AB55E24F6B3CA4B');
        $this->addSql('ALTER TABLE produitsponsoring DROP FOREIGN KEY IF EXISTS FK_2D525AFF50EAE44');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY IF EXISTS FK_CE6064046B3CA4B');

        // Optionally recreate utilisateur and revert FKs (adjust as needed)
        $this->addSql('CREATE TABLE IF NOT EXISTS utilisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, nationalite VARCHAR(255) NOT NULL, genre VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, permission TINYINT(1) DEFAULT NULL, statut TINYINT(1) DEFAULT NULL, verification_token VARCHAR(255) DEFAULT NULL, is_verified TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE IF EXISTS User');
    }
}