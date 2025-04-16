<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250414105615 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Set statut to true where null and make column not nullable';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('UPDATE utilisateur SET statut = true WHERE statut IS NULL');
        $this->addSql('ALTER TABLE utilisateur ALTER COLUMN statut SET NOT NULL');
        $this->addSql('ALTER TABLE utilisateur ALTER COLUMN statut SET DEFAULT true');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE utilisateur ALTER COLUMN statut DROP NOT NULL');
        $this->addSql('ALTER TABLE utilisateur ALTER COLUMN statut DROP DEFAULT');
    }
}