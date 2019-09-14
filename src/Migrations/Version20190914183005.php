<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190914183005 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sermon_series DROP FOREIGN KEY FK_2218EC0E212AE469');
        $this->addSql('CREATE TABLE event (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', speaker_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', date DATE NOT NULL, apm VARCHAR(3) NOT NULL, reading VARCHAR(255) NOT NULL, second_reading VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, corrupt TINYINT(1) NOT NULL, is_public TINYINT(1) NOT NULL, tags LONGTEXT NOT NULL, public_comments LONGTEXT NOT NULL, private_comments LONGTEXT NOT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_3BAE0AA7D04A0F27 (speaker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_series (event_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', series_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_889767F71F7E88B (event_id), INDEX IDX_889767F5278319C (series_id), PRIMARY KEY(event_id, series_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attachment_metadata (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', mime_type VARCHAR(255) NOT NULL, content_length VARCHAR(255) NOT NULL, file_location VARCHAR(50) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_attachment (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_attachment_event (event_attachment_id INT NOT NULL, event_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_AB20B746203D5627 (event_attachment_id), INDEX IDX_AB20B74671F7E88B (event_id), PRIMARY KEY(event_attachment_id, event_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7D04A0F27 FOREIGN KEY (speaker_id) REFERENCES speaker (id)');
        $this->addSql('ALTER TABLE event_series ADD CONSTRAINT FK_889767F71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_series ADD CONSTRAINT FK_889767F5278319C FOREIGN KEY (series_id) REFERENCES series (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_attachment_event ADD CONSTRAINT FK_AB20B746203D5627 FOREIGN KEY (event_attachment_id) REFERENCES event_attachment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_attachment_event ADD CONSTRAINT FK_AB20B74671F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE sermon');
        $this->addSql('DROP TABLE sermon_series');
        $this->addSql('DROP TABLE uploaded_file_metadata');
        $this->addSql('DROP INDEX log_class_lookup_idx ON ext_log_entries');
        $this->addSql('DROP INDEX log_version_lookup_idx ON ext_log_entries');
        $this->addSql('DROP INDEX log_user_lookup_idx ON ext_log_entries');
        $this->addSql('CREATE INDEX log_class_lookup_idx ON ext_log_entries (object_class)');
        $this->addSql('CREATE INDEX log_version_lookup_idx ON ext_log_entries (object_id, object_class, version)');
        $this->addSql('CREATE INDEX log_user_lookup_idx ON ext_log_entries (username)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE event_series DROP FOREIGN KEY FK_889767F71F7E88B');
        $this->addSql('ALTER TABLE event_attachment_event DROP FOREIGN KEY FK_AB20B74671F7E88B');
        $this->addSql('ALTER TABLE event_attachment_event DROP FOREIGN KEY FK_AB20B746203D5627');
        $this->addSql('CREATE TABLE sermon (id CHAR(36) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', speaker_id CHAR(36) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', date DATE NOT NULL, apm VARCHAR(3) NOT NULL COLLATE utf8mb4_unicode_ci, reading VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, second_reading VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, corrupt TINYINT(1) NOT NULL, is_public TINYINT(1) NOT NULL, tags LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, public_comments LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, private_comments LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_A4F02DA2D04A0F27 (speaker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE sermon_series (sermon_id CHAR(36) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', series_id CHAR(36) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', INDEX IDX_2218EC0E212AE469 (sermon_id), INDEX IDX_2218EC0E5278319C (series_id), PRIMARY KEY(sermon_id, series_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE uploaded_file_metadata (id CHAR(36) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', mime_type VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, content_length VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, file_location VARCHAR(50) DEFAULT NULL COLLATE utf8mb4_unicode_ci, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE sermon ADD CONSTRAINT FK_A4F02DA2D04A0F27 FOREIGN KEY (speaker_id) REFERENCES speaker (id)');
        $this->addSql('ALTER TABLE sermon_series ADD CONSTRAINT FK_2218EC0E212AE469 FOREIGN KEY (sermon_id) REFERENCES sermon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sermon_series ADD CONSTRAINT FK_2218EC0E5278319C FOREIGN KEY (series_id) REFERENCES series (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_series');
        $this->addSql('DROP TABLE attachment_metadata');
        $this->addSql('DROP TABLE event_attachment');
        $this->addSql('DROP TABLE event_attachment_event');
        $this->addSql('DROP INDEX log_class_lookup_idx ON ext_log_entries');
        $this->addSql('DROP INDEX log_user_lookup_idx ON ext_log_entries');
        $this->addSql('DROP INDEX log_version_lookup_idx ON ext_log_entries');
        $this->addSql('CREATE INDEX log_class_lookup_idx ON ext_log_entries (object_class(191))');
        $this->addSql('CREATE INDEX log_user_lookup_idx ON ext_log_entries (username(191))');
        $this->addSql('CREATE INDEX log_version_lookup_idx ON ext_log_entries (object_id, object_class(191), version)');
    }
}
