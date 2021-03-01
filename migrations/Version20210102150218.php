<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210102150218 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attachment_metadata (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', event_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', type_id INT DEFAULT NULL, mime_type VARCHAR(255) NOT NULL, extension VARCHAR(10) NOT NULL, content_length VARCHAR(255) NOT NULL, file_location VARCHAR(50) DEFAULT NULL, complete TINYINT(1) NOT NULL, hash VARCHAR(128) NOT NULL, hash_algo VARCHAR(10) NOT NULL, is_public TINYINT(1) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_4B07875471F7E88B (event_id), INDEX IDX_4B078754C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attachment_metadata_type (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(30) NOT NULL, name VARCHAR(20) NOT NULL, description VARCHAR(255) NOT NULL, can_be_public TINYINT(1) DEFAULT \'1\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_series (event_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', series_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_889767F71F7E88B (event_id), INDEX IDX_889767F5278319C (series_id), PRIMARY KEY(event_id, series_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_url (id INT AUTO_INCREMENT NOT NULL, event_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', title VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_E5B9807D71F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE series (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, complete TINYINT(1) NOT NULL, is_public TINYINT(1) NOT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE speaker (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(255) NOT NULL, organisation VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_7B85DB615E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attachment_metadata ADD CONSTRAINT FK_4B07875471F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE attachment_metadata ADD CONSTRAINT FK_4B078754C54C8C93 FOREIGN KEY (type_id) REFERENCES attachment_metadata_type (id)');
        $this->addSql('ALTER TABLE event_series ADD CONSTRAINT FK_889767F71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_series ADD CONSTRAINT FK_889767F5278319C FOREIGN KEY (series_id) REFERENCES series (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_url ADD CONSTRAINT FK_E5B9807D71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE event ADD speaker_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', ADD reading VARCHAR(255) NOT NULL, ADD second_reading VARCHAR(255) NOT NULL, ADD title VARCHAR(255) NOT NULL, ADD corrupt TINYINT(1) NOT NULL, ADD is_public TINYINT(1) NOT NULL, ADD tags LONGTEXT NOT NULL, ADD public_comments LONGTEXT NOT NULL, ADD private_comments LONGTEXT NOT NULL, ADD legacy_id VARCHAR(10) DEFAULT NULL, ADD deleted_at DATETIME DEFAULT NULL, ADD created_at DATETIME DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL, CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7D04A0F27 FOREIGN KEY (speaker_id) REFERENCES speaker (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7D04A0F27 ON event (speaker_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attachment_metadata DROP FOREIGN KEY FK_4B078754C54C8C93');
        $this->addSql('ALTER TABLE event_series DROP FOREIGN KEY FK_889767F5278319C');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7D04A0F27');
        $this->addSql('DROP TABLE attachment_metadata');
        $this->addSql('DROP TABLE attachment_metadata_type');
        $this->addSql('DROP TABLE event_series');
        $this->addSql('DROP TABLE event_url');
        $this->addSql('DROP TABLE series');
        $this->addSql('DROP TABLE speaker');
        $this->addSql('DROP INDEX IDX_3BAE0AA7D04A0F27 ON event');
        $this->addSql('ALTER TABLE event DROP speaker_id, DROP reading, DROP second_reading, DROP title, DROP corrupt, DROP is_public, DROP tags, DROP public_comments, DROP private_comments, DROP legacy_id, DROP deleted_at, DROP created_at, DROP updated_at, CHANGE id id CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:uuid)\'');
    }
}
