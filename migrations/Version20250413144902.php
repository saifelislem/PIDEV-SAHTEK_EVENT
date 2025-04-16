<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250413144902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_9EBB74FBE7927C74 ON sponsor_pending
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sponsor_pending CHANGE mot_de_passe mot_de_passe VARCHAR(255) DEFAULT NULL, CHANGE produit_description produit_description LONGTEXT NOT NULL, CHANGE contrat_montant contrat_montant NUMERIC(10, 2) DEFAULT NULL, CHANGE status status VARCHAR(255) NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE sponsor_pending CHANGE mot_de_passe mot_de_passe VARCHAR(255) NOT NULL, CHANGE status status VARCHAR(20) NOT NULL, CHANGE produit_description produit_description LONGTEXT DEFAULT NULL, CHANGE contrat_montant contrat_montant DOUBLE PRECISION NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_9EBB74FBE7927C74 ON sponsor_pending (email)
        SQL);
    }
}
