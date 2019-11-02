<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190914222324 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD email VARCHAR(30) NOT NULL, ADD deleted_at DATETIME DEFAULT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE attachment_metadata ADD event_attachment_id INT NOT NULL');
        $this->addSql('ALTER TABLE attachment_metadata ADD CONSTRAINT FK_4B078754203D5627 FOREIGN KEY (event_attachment_id) REFERENCES event_attachment (id)');
        $this->addSql('CREATE INDEX IDX_4B078754203D5627 ON attachment_metadata (event_attachment_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE attachment_metadata DROP FOREIGN KEY FK_4B078754203D5627');
        $this->addSql('DROP INDEX IDX_4B078754203D5627 ON attachment_metadata');
        $this->addSql('ALTER TABLE attachment_metadata DROP event_attachment_id');
        $this->addSql('ALTER TABLE user DROP email, DROP deleted_at, DROP created_at, DROP updated_at');
    }
}
