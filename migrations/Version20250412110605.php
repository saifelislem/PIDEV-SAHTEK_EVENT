<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250412110605 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE feedback DROP id_user, DROP id_evenement
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reclamation CHANGE message message VARCHAR(255) NOT NULL, CHANGE statut statut VARCHAR(255) DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE feedback ADD id_user INT NOT NULL, ADD id_evenement INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reclamation CHANGE message message VARCHAR(1000) NOT NULL, CHANGE statut statut VARCHAR(50) DEFAULT NULL
        SQL);
    }
}
