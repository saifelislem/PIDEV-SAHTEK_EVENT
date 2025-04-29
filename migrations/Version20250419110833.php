<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration to update contrat_sponsoring, evenement, and utilisateur schema.
 * Skips adding id_evenement if it already exists and conditionally adds foreign key and index.
 */
final class Version20250419110833 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update contrat_sponsoring, evenement, and utilisateur schema';
    }

    public function up(Schema $schema): void
    {
        // Conditionally add foreign key if it doesn't exist
        $this->addSql(<<<'SQL'
            SET @fk_exists = (SELECT COUNT(*) 
                              FROM information_schema.table_constraints 
                              WHERE table_name = 'contrat_sponsoring' 
                              AND constraint_name = 'FK_596F50658B13D439');
            SET @sql = IF(@fk_exists = 0,
                          'ALTER TABLE contrat_sponsoring ADD CONSTRAINT FK_596F50658B13D439 FOREIGN KEY (id_evenement) REFERENCES evenement (id)',
                          'SELECT "Foreign key FK_596F50658B13D439 already exists"');
            PREPARE stmt FROM @sql;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt;
        SQL);

        // Conditionally add index if it doesn't exist
        $this->addSql(<<<'SQL'
            SET @index_exists = (SELECT COUNT(*) 
                                 FROM information_schema.statistics 
                                 WHERE table_name = 'contrat_sponsoring' 
                                 AND index_name = 'IDX_596F50658B13D439');
            SET @sql = IF(@index_exists = 0,
                          'CREATE INDEX IDX_596F50658B13D439 ON contrat_sponsoring (id_evenement)',
                          'SELECT "Index IDX_596F50658B13D439 already exists"');
            PREPARE stmt FROM @sql;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt;
        SQL);

        // Modify evenement.statut to be nullable
        $this->addSql(<<<'SQL'
            ALTER TABLE evenement CHANGE statut statut VARCHAR(255) DEFAULT NULL
        SQL);

        // Modify utilisateur.role and is_verified
        $this->addSql(<<<'SQL'
            ALTER TABLE utilisateur 
            CHANGE role role JSON NOT NULL COMMENT '(DC2Type:json)', 
            CHANGE is_verified is_verified TINYINT(1) DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // Conditionally drop foreign key if it exists
        $this->addSql(<<<'SQL'
            SET @fk_exists = (SELECT COUNT(*) 
                              FROM information_schema.table_constraints 
                              WHERE table_name = 'contrat_sponsoring' 
                              AND constraint_name = 'FK_596F50658B13D439');
            SET @sql = IF(@fk_exists > 0,
                          'ALTER TABLE contrat_sponsoring DROP FOREIGN KEY FK_596F50658B13D439',
                          'SELECT "Foreign key FK_596F50658B13D439 does not exist"');
            PREPARE stmt FROM @sql;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt;
        SQL);

        // Conditionally drop index if it exists
        $this->addSql(<<<'SQL'
            SET @index_exists = (SELECT COUNT(*) 
                                 FROM information_schema.statistics 
                                 WHERE table_name = 'contrat_sponsoring' 
                                 AND index_name = 'IDX_596F50658B13D439');
            SET @sql = IF(@index_exists > 0,
                          'DROP INDEX IDX_596F50658B13D439 ON contrat_sponsoring',
                          'SELECT "Index IDX_596F50658B13D439 does not exist"');
            PREPARE stmt FROM @sql;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt;
        SQL);

        // Revert evenement.statut to NOT NULL
        $this->addSql(<<<'SQL'
            ALTER TABLE evenement CHANGE statut statut VARCHAR(255) NOT NULL
        SQL);

        // Revert utilisateur.role and is_verified
        $this->addSql(<<<'SQL'
            ALTER TABLE utilisateur 
            CHANGE role role JSON NOT NULL COMMENT '(DC2Type:json)', 
            CHANGE is_verified is_verified TINYINT(1) DEFAULT 0
        SQL);
    }
}