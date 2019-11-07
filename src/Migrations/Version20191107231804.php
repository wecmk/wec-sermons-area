<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191107231804 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE attachment_metadata DROP FOREIGN KEY FK_4B078754203D5627');
        $this->addSql('ALTER TABLE event_attachment_event DROP FOREIGN KEY FK_AB20B746203D5627');
        $this->addSql('CREATE TABLE attachment_metadata_type (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(10) NOT NULL, name VARCHAR(20) NOT NULL, description VARCHAR(255) NOT NULL, can_be_public TINYINT(1) DEFAULT \'1\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE event_attachment');
        $this->addSql('DROP TABLE event_attachment_event');
        $this->addSql('DROP INDEX IDX_4B078754203D5627 ON attachment_metadata');
        $this->addSql('ALTER TABLE attachment_metadata ADD event_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', ADD is_public TINYINT(1) NOT NULL, CHANGE event_attachment_id type_id INT NOT NULL');
        $this->addSql('ALTER TABLE attachment_metadata ADD CONSTRAINT FK_4B07875471F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE attachment_metadata ADD CONSTRAINT FK_4B078754C54C8C93 FOREIGN KEY (type_id) REFERENCES attachment_metadata_type (id)');
        $this->addSql('CREATE INDEX IDX_4B07875471F7E88B ON attachment_metadata (event_id)');
        $this->addSql('CREATE INDEX IDX_4B078754C54C8C93 ON attachment_metadata (type_id)');   
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE attachment_metadata DROP FOREIGN KEY FK_4B078754C54C8C93');
        $this->addSql('CREATE TABLE event_attachment (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE event_attachment_event (event_attachment_id INT NOT NULL, event_id CHAR(36) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', INDEX IDX_AB20B746203D5627 (event_attachment_id), INDEX IDX_AB20B74671F7E88B (event_id), PRIMARY KEY(event_attachment_id, event_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE event_attachment_event ADD CONSTRAINT FK_AB20B746203D5627 FOREIGN KEY (event_attachment_id) REFERENCES event_attachment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_attachment_event ADD CONSTRAINT FK_AB20B74671F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE attachment_metadata_type');
        $this->addSql('ALTER TABLE attachment_metadata DROP FOREIGN KEY FK_4B07875471F7E88B');
        $this->addSql('DROP INDEX IDX_4B07875471F7E88B ON attachment_metadata');
        $this->addSql('DROP INDEX IDX_4B078754C54C8C93 ON attachment_metadata');
        $this->addSql('ALTER TABLE attachment_metadata DROP event_id, DROP is_public, CHANGE type_id event_attachment_id INT NOT NULL');
        $this->addSql('ALTER TABLE attachment_metadata ADD CONSTRAINT FK_4B078754203D5627 FOREIGN KEY (event_attachment_id) REFERENCES event_attachment (id)');
        $this->addSql('CREATE INDEX IDX_4B078754203D5627 ON attachment_metadata (event_attachment_id)');
    }
}
