<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210409003044 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7D04A0F27');
        $this->addSql('DROP TABLE event_url');
        $this->addSql('DROP TABLE speaker');
        $this->addSql('ALTER TABLE attachment_metadata CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', CHANGE event_id event_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('DROP INDEX IDX_3BAE0AA7D04A0F27 ON event');
        $this->addSql('ALTER TABLE event ADD you_tube_link VARCHAR(255) NOT NULL, ADD speaker VARCHAR(255) NOT NULL, DROP speaker_id, CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE event_series CHANGE event_id event_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', CHANGE series_id series_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE series CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event_url (id INT AUTO_INCREMENT NOT NULL, event_id CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\', title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, url VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_E5B9807D71F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE speaker (id CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\', name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, organisation VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, website VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, deleted_at DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_7B85DB615E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE event_url ADD CONSTRAINT FK_E5B9807D71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE attachment_metadata CHANGE id id CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\', CHANGE event_id event_id CHAR(36) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE event ADD speaker_id CHAR(36) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\', DROP you_tube_link, DROP speaker, CHANGE id id CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7D04A0F27 FOREIGN KEY (speaker_id) REFERENCES speaker (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7D04A0F27 ON event (speaker_id)');
        $this->addSql('ALTER TABLE event_series CHANGE event_id event_id CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\', CHANGE series_id series_id CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE series CHANGE id id CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\'');
    }
}
