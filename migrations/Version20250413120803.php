<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250413120803 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE contrat_sponsoring DROP FOREIGN KEY FK_596F506545ABF106
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_596F506545ABF106 ON contrat_sponsoring
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contrat_sponsoring DROP id_evenementAssocie
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE contrat_sponsoring ADD id_evenementAssocie INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contrat_sponsoring ADD CONSTRAINT FK_596F506545ABF106 FOREIGN KEY (id_evenementAssocie) REFERENCES evenement (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_596F506545ABF106 ON contrat_sponsoring (id_evenementAssocie)
        SQL);
    }
}
