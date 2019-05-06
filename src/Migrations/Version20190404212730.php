<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190404212730 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE public_sermon (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, speaker VARCHAR(255) NOT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE series (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, complete TINYINT(1) NOT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_series (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, current TINYINT(1) NOT NULL, number INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact_us_form_results (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, message VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_qa (id INT AUTO_INCREMENT NOT NULL, question_series_id INT NOT NULL, number INT NOT NULL, question VARCHAR(255) NOT NULL, answer VARCHAR(255) NOT NULL, publish_date DATETIME NOT NULL, INDEX IDX_EC569A695BAD7507 (question_series_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bible_books (id INT AUTO_INCREMENT NOT NULL, book VARCHAR(20) NOT NULL, sort INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sermon (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', speaker_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', date DATE NOT NULL, apm VARCHAR(3) NOT NULL, reading VARCHAR(255) NOT NULL, second_reading VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, corrupt TINYINT(1) NOT NULL, is_public TINYINT(1) NOT NULL, tags LONGTEXT NOT NULL, public_comments LONGTEXT NOT NULL, private_comments LONGTEXT NOT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_A4F02DA2D04A0F27 (speaker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sermon_series (sermon_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', series_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_2218EC0E212AE469 (sermon_id), INDEX IDX_2218EC0E5278319C (series_id), PRIMARY KEY(sermon_id, series_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE speaker (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, organisation VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ext_log_entries (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(8) NOT NULL, logged_at DATETIME NOT NULL, object_id VARCHAR(64) DEFAULT NULL, object_class VARCHAR(255) NOT NULL, version INT NOT NULL, data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', username VARCHAR(255) DEFAULT NULL, INDEX log_class_lookup_idx (object_class), INDEX log_date_lookup_idx (logged_at), INDEX log_user_lookup_idx (username), INDEX log_version_lookup_idx (object_id, object_class, version), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('ALTER TABLE question_qa ADD CONSTRAINT FK_EC569A695BAD7507 FOREIGN KEY (question_series_id) REFERENCES question_series (id)');
        $this->addSql('ALTER TABLE sermon ADD CONSTRAINT FK_A4F02DA2D04A0F27 FOREIGN KEY (speaker_id) REFERENCES speaker (id)');
        $this->addSql('ALTER TABLE sermon_series ADD CONSTRAINT FK_2218EC0E212AE469 FOREIGN KEY (sermon_id) REFERENCES sermon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sermon_series ADD CONSTRAINT FK_2218EC0E5278319C FOREIGN KEY (series_id) REFERENCES series (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sermon_series DROP FOREIGN KEY FK_2218EC0E5278319C');
        $this->addSql('ALTER TABLE question_qa DROP FOREIGN KEY FK_EC569A695BAD7507');
        $this->addSql('ALTER TABLE sermon_series DROP FOREIGN KEY FK_2218EC0E212AE469');
        $this->addSql('ALTER TABLE sermon DROP FOREIGN KEY FK_A4F02DA2D04A0F27');
        $this->addSql('DROP TABLE public_sermon');
        $this->addSql('DROP TABLE series');
        $this->addSql('DROP TABLE question_series');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE contact_us_form_results');
        $this->addSql('DROP TABLE question_qa');
        $this->addSql('DROP TABLE bible_books');
        $this->addSql('DROP TABLE sermon');
        $this->addSql('DROP TABLE sermon_series');
        $this->addSql('DROP TABLE speaker');
        $this->addSql('DROP TABLE ext_log_entries');
    }
}
