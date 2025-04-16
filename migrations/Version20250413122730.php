<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250413122730 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE sponsor_pending (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, nationalite VARCHAR(255) NOT NULL, genre VARCHAR(255) NOT NULL, produit_nom VARCHAR(255) NOT NULL, produit_description LONGTEXT DEFAULT NULL, produit_quantite INT NOT NULL, produit_prix DOUBLE PRECISION NOT NULL, produit_image VARCHAR(255) DEFAULT NULL, contrat_montant DOUBLE PRECISION NOT NULL, contrat_description LONGTEXT DEFAULT NULL, status VARCHAR(20) NOT NULL, UNIQUE INDEX UNIQ_9EBB74FBE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE sponsor_pending
        SQL);
    }
}
