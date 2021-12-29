<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210424235154 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Creates inital database schema';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("SET FOREIGN_KEY_CHECKS = 0");
        $this->addSql("DROP TABLE IF EXISTS `attachment_metadata`");
        $this->addSql("DROP TABLE IF EXISTS `attachment_metadata_type`");
        $this->addSql("DROP TABLE IF EXISTS `bible_books`");
        $this->addSql("DROP TABLE IF EXISTS `event`");
        $this->addSql("DROP TABLE IF EXISTS `event_series`");
        $this->addSql("DROP TABLE IF EXISTS `series`");
        $this->addSql("DROP TABLE IF EXISTS `teams`");
        $this->addSql("DROP TABLE IF EXISTS `user`");
        $this->addSql("SET FOREIGN_KEY_CHECKS = 1");
        $this->addSql('CREATE TABLE attachment_metadata (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, type_id INT DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', mime_type VARCHAR(255) NOT NULL, extension VARCHAR(10) NOT NULL, content_length VARCHAR(255) NOT NULL, complete TINYINT(1) NOT NULL, hash VARCHAR(128) NOT NULL, hash_algo VARCHAR(10) NOT NULL, is_public TINYINT(1) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_4B078754D17F50A6 (uuid), INDEX IDX_4B07875471F7E88B (event_id), INDEX IDX_4B078754C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attachment_metadata_type (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(30) NOT NULL, name VARCHAR(20) NOT NULL, description VARCHAR(255) NOT NULL, can_be_public TINYINT(1) DEFAULT \'1\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bible_books (id INT AUTO_INCREMENT NOT NULL, book VARCHAR(16) NOT NULL, sort INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', date DATE NOT NULL, apm VARCHAR(3) NOT NULL, reading VARCHAR(255) NOT NULL, second_reading VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, corrupt TINYINT(1) NOT NULL, is_public TINYINT(1) NOT NULL, tags LONGTEXT NOT NULL, public_comments LONGTEXT NOT NULL, private_comments LONGTEXT NOT NULL, legacy_id VARCHAR(10) DEFAULT NULL, you_tube_link VARCHAR(255) DEFAULT NULL, speaker VARCHAR(255) NOT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_3BAE0AA7D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_series (event_id INT NOT NULL, series_id INT NOT NULL, INDEX IDX_889767F71F7E88B (event_id), INDEX IDX_889767F5278319C (series_id), PRIMARY KEY(event_id, series_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE series (id INT AUTO_INCREMENT NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, complete TINYINT(1) NOT NULL, is_public TINYINT(1) NOT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_3A10012DD17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE teams (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(20) NOT NULL, link VARCHAR(255) NOT NULL, image_as_base64 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attachment_metadata ADD CONSTRAINT FK_4B07875471F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE attachment_metadata ADD CONSTRAINT FK_4B078754C54C8C93 FOREIGN KEY (type_id) REFERENCES attachment_metadata_type (id)');
        $this->addSql('ALTER TABLE event_series ADD CONSTRAINT FK_889767F71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_series ADD CONSTRAINT FK_889767F5278319C FOREIGN KEY (series_id) REFERENCES series (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attachment_metadata DROP FOREIGN KEY FK_4B078754C54C8C93');
        $this->addSql('ALTER TABLE attachment_metadata DROP FOREIGN KEY FK_4B07875471F7E88B');
        $this->addSql('ALTER TABLE event_series DROP FOREIGN KEY FK_889767F71F7E88B');
        $this->addSql('ALTER TABLE event_series DROP FOREIGN KEY FK_889767F5278319C');
        $this->addSql('DROP TABLE attachment_metadata');
        $this->addSql('DROP TABLE attachment_metadata_type');
        $this->addSql('DROP TABLE bible_books');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_series');
        $this->addSql('DROP TABLE series');
        $this->addSql('DROP TABLE teams');
        $this->addSql('DROP TABLE user');
    }
}
