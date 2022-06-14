<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220613123730 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D6B70FF80C');
        $this->addSql('DROP INDEX IDX_5E90F6D6B70FF80C ON inscription');
        $this->addSql('ALTER TABLE inscription DROP rp_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inscription ADD rp_id INT NOT NULL');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D6B70FF80C FOREIGN KEY (rp_id) REFERENCES rp (id)');
        $this->addSql('CREATE INDEX IDX_5E90F6D6B70FF80C ON inscription (rp_id)');
    }
}
