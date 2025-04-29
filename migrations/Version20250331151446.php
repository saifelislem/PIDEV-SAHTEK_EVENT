<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250331151446 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE contrat_sponsoring (id INT AUTO_INCREMENT NOT NULL, id_utilisateur INT DEFAULT NULL, montant DOUBLE PRECISION DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, id_evenementAssocie INT DEFAULT NULL, INDEX IDX_596F506550EAE44 (id_utilisateur), INDEX IDX_596F506545ABF106 (id_evenementAssocie), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE contrat_produit_sponsoring (id_contrat INT NOT NULL, id_produit INT NOT NULL, INDEX IDX_CCD21D85BEA930E3 (id_contrat), INDEX IDX_CCD21D85F7384557 (id_produit), PRIMARY KEY(id_contrat, id_produit)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date DATETIME NOT NULL, lieu VARCHAR(255) NOT NULL, statut VARCHAR(255) NOT NULL, capacite_max INT NOT NULL, image VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_B26681E6B3CA4B (id_user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE feedback (id INT AUTO_INCREMENT NOT NULL, pass VARCHAR(255) NOT NULL, message VARCHAR(255) NOT NULL, note INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE participation (id INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, id_evenement INT DEFAULT NULL, date_inscription DATETIME NOT NULL, motif_annulation LONGTEXT DEFAULT NULL, moyen_paiement VARCHAR(255) DEFAULT NULL, INDEX IDX_AB55E24F6B3CA4B (id_user), INDEX IDX_AB55E24F8B13D439 (id_evenement), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE produitsponsoring (id INT AUTO_INCREMENT NOT NULL, id_utilisateur INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, quantite INT DEFAULT NULL, prix DOUBLE PRECISION DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, INDEX IDX_2D525AFF50EAE44 (id_utilisateur), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE profil (id INT AUTO_INCREMENT NOT NULL, id_utilisateur INT DEFAULT NULL, email VARCHAR(255) NOT NULL, num_tel VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, id_evenement INT DEFAULT NULL, message VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, pass VARCHAR(255) NOT NULL, statut VARCHAR(255) DEFAULT NULL, INDEX IDX_CE6064046B3CA4B (id_user), INDEX IDX_CE6064048B13D439 (id_evenement), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, cout DOUBLE PRECISION DEFAULT NULL, id_evenementAssocie INT DEFAULT NULL, INDEX IDX_E19D9AD245ABF106 (id_evenementAssocie), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE suivi_reclamation (id INT AUTO_INCREMENT NOT NULL, id_reclamation INT DEFAULT NULL, status VARCHAR(255) NOT NULL, commentaire VARCHAR(255) NOT NULL, INDEX IDX_1F716019D672A9F3 (id_reclamation), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE support (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, id_evenement_associe INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE supportpermission (id INT AUTO_INCREMENT NOT NULL, support_id INT DEFAULT NULL, permission_type VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, start_date DATE DEFAULT NULL, end_date DATE DEFAULT NULL, INDEX IDX_1B07A801315B405 (support_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE transport (id INT AUTO_INCREMENT NOT NULL, id_service INT DEFAULT NULL, date DATE DEFAULT NULL, heure_depart TIME DEFAULT NULL, point_depart VARCHAR(255) DEFAULT NULL, destination VARCHAR(255) DEFAULT NULL, vehicule VARCHAR(255) DEFAULT NULL, id_evenementAssocie INT DEFAULT NULL, INDEX IDX_66AB212E45ABF106 (id_evenementAssocie), INDEX IDX_66AB212E3F0033A2 (id_service), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, nationalite VARCHAR(255) NOT NULL, genre VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, permission TINYINT(1) DEFAULT NULL, statut TINYINT(1) DEFAULT NULL, verification_token VARCHAR(255) DEFAULT NULL, is_verified TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contrat_sponsoring ADD CONSTRAINT FK_596F506550EAE44 FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contrat_sponsoring ADD CONSTRAINT FK_596F506545ABF106 FOREIGN KEY (id_evenementAssocie) REFERENCES evenement (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contrat_produit_sponsoring ADD CONSTRAINT FK_CCD21D85BEA930E3 FOREIGN KEY (id_contrat) REFERENCES contrat_sponsoring (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contrat_produit_sponsoring ADD CONSTRAINT FK_CCD21D85F7384557 FOREIGN KEY (id_produit) REFERENCES produitsponsoring (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evenement ADD CONSTRAINT FK_B26681E6B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F6B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F8B13D439 FOREIGN KEY (id_evenement) REFERENCES evenement (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE produitsponsoring ADD CONSTRAINT FK_2D525AFF50EAE44 FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064046B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064048B13D439 FOREIGN KEY (id_evenement) REFERENCES evenement (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE service ADD CONSTRAINT FK_E19D9AD245ABF106 FOREIGN KEY (id_evenementAssocie) REFERENCES evenement (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE suivi_reclamation ADD CONSTRAINT FK_1F716019D672A9F3 FOREIGN KEY (id_reclamation) REFERENCES reclamation (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE supportpermission ADD CONSTRAINT FK_1B07A801315B405 FOREIGN KEY (support_id) REFERENCES support (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE transport ADD CONSTRAINT FK_66AB212E45ABF106 FOREIGN KEY (id_evenementAssocie) REFERENCES evenement (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE transport ADD CONSTRAINT FK_66AB212E3F0033A2 FOREIGN KEY (id_service) REFERENCES service (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE contrat_sponsoring DROP FOREIGN KEY FK_596F506550EAE44
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contrat_sponsoring DROP FOREIGN KEY FK_596F506545ABF106
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contrat_produit_sponsoring DROP FOREIGN KEY FK_CCD21D85BEA930E3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contrat_produit_sponsoring DROP FOREIGN KEY FK_CCD21D85F7384557
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evenement DROP FOREIGN KEY FK_B26681E6B3CA4B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F6B3CA4B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F8B13D439
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE produitsponsoring DROP FOREIGN KEY FK_2D525AFF50EAE44
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reclamation DROP FOREIGN KEY FK_CE6064046B3CA4B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reclamation DROP FOREIGN KEY FK_CE6064048B13D439
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD245ABF106
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE suivi_reclamation DROP FOREIGN KEY FK_1F716019D672A9F3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE supportpermission DROP FOREIGN KEY FK_1B07A801315B405
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE transport DROP FOREIGN KEY FK_66AB212E45ABF106
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE transport DROP FOREIGN KEY FK_66AB212E3F0033A2
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE contrat_sponsoring
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE contrat_produit_sponsoring
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE evenement
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE feedback
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE participation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE produitsponsoring
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE profil
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reclamation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE service
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE suivi_reclamation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE support
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE supportpermission
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE transport
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE utilisateur
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
