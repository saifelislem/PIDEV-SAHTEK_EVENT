<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250409161131 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE feedback ADD id_user INT NOT NULL, ADD id_evenement INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feedback ADD CONSTRAINT FK_D22944586B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feedback ADD CONSTRAINT FK_D22944588B13D439 FOREIGN KEY (id_evenement) REFERENCES evenement (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D22944586B3CA4B ON feedback (id_user)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D22944588B13D439 ON feedback (id_evenement)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE feedback DROP FOREIGN KEY FK_D22944586B3CA4B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feedback DROP FOREIGN KEY FK_D22944588B13D439
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_D22944586B3CA4B ON feedback
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_D22944588B13D439 ON feedback
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feedback DROP id_user, DROP id_evenement
        SQL);
    }
}
