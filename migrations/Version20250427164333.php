<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250427164333 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE sponsor_pending
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contrat_sponsoring DROP FOREIGN KEY FK_596F50658B13D439
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_596F50658B13D439 ON contrat_sponsoring
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contrat_sponsoring CHANGE id_evenement id_evenementAssocie INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contrat_sponsoring ADD CONSTRAINT FK_596F506545ABF106 FOREIGN KEY (id_evenementAssocie) REFERENCES evenement (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_596F506545ABF106 ON contrat_sponsoring (id_evenementAssocie)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evenement ADD id_user INT DEFAULT NULL, CHANGE statut statut VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evenement ADD CONSTRAINT FK_B26681E6B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B26681E6B3CA4B ON evenement (id_user)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feedback DROP id_user, DROP id_evenement
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE supportpermission ADD role VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE utilisateur DROP reset_token, CHANGE statut statut TINYINT(1) DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE sponsor_pending (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, prenom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, mot_de_passe VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, nationalite VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, genre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, produit_nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, produit_description LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, produit_quantite INT NOT NULL, produit_prix DOUBLE PRECISION NOT NULL, produit_image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, contrat_montant NUMERIC(10, 2) DEFAULT NULL, contrat_description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, status VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contrat_sponsoring DROP FOREIGN KEY FK_596F506545ABF106
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_596F506545ABF106 ON contrat_sponsoring
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contrat_sponsoring CHANGE id_evenementAssocie id_evenement INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contrat_sponsoring ADD CONSTRAINT FK_596F50658B13D439 FOREIGN KEY (id_evenement) REFERENCES evenement (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_596F50658B13D439 ON contrat_sponsoring (id_evenement)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evenement DROP FOREIGN KEY FK_B26681E6B3CA4B
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_B26681E6B3CA4B ON evenement
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evenement DROP id_user, CHANGE statut statut VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feedback ADD id_user INT NOT NULL, ADD id_evenement INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE supportpermission DROP role
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE utilisateur ADD reset_token VARCHAR(255) DEFAULT NULL, CHANGE statut statut TINYINT(1) DEFAULT 1 NOT NULL
        SQL);
    }
}
