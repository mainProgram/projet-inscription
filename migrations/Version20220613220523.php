<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220613220523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande CHANGE motif motif TINYINT(1) NOT NULL, CHANGE traitement traitement INT NOT NULL');
        $this->addSql('ALTER TABLE etudiant CHANGE matricule matricule VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande CHANGE motif motif TINYINT(1) NOT NULL COMMENT \'1 suspend, 0 annuler\', CHANGE traitement traitement INT DEFAULT NULL COMMENT \'0 en cours, 1 accepté, -1 refus\'');
        $this->addSql('ALTER TABLE etudiant CHANGE matricule matricule VARCHAR(255) DEFAULT NULL');
    }
}
