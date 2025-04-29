<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250409132329 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE reclamation CHANGE id_user id_user INT NOT NULL, CHANGE id_evenement id_evenement INT NOT NULL, CHANGE message message VARCHAR(1000) NOT NULL, CHANGE statut statut VARCHAR(50) DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE reclamation CHANGE id_user id_user INT DEFAULT NULL, CHANGE id_evenement id_evenement INT DEFAULT NULL, CHANGE message message VARCHAR(255) NOT NULL, CHANGE statut statut VARCHAR(255) DEFAULT NULL
        SQL);
    }
}
