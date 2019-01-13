<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190113230151 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE public_sermon (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, speaker VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE series (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, complete TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sermon (id INT AUTO_INCREMENT NOT NULL, apm_id INT DEFAULT NULL, date DATE NOT NULL, reading VARCHAR(255) NOT NULL, second_reading VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, corrupt TINYINT(1) NOT NULL, is_public TINYINT(1) NOT NULL, tags LONGTEXT NOT NULL, public_comments LONGTEXT NOT NULL, private_comments LONGTEXT NOT NULL, INDEX IDX_A4F02DA2DBF4AE6F (apm_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sermon_series (sermon_id INT NOT NULL, series_id INT NOT NULL, INDEX IDX_2218EC0E212AE469 (sermon_id), INDEX IDX_2218EC0E5278319C (series_id), PRIMARY KEY(sermon_id, series_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sermon_speaker (sermon_id INT NOT NULL, speaker_id INT NOT NULL, INDEX IDX_D9FAA2FE212AE469 (sermon_id), INDEX IDX_D9FAA2FED04A0F27 (speaker_id), PRIMARY KEY(sermon_id, speaker_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE speaker (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, organisation VARCHAR(255) NOT NULL, website VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE apm (id INT AUTO_INCREMENT NOT NULL, apm VARCHAR(3) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sermon ADD CONSTRAINT FK_A4F02DA2DBF4AE6F FOREIGN KEY (apm_id) REFERENCES apm (id)');
        $this->addSql('ALTER TABLE sermon_series ADD CONSTRAINT FK_2218EC0E212AE469 FOREIGN KEY (sermon_id) REFERENCES sermon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sermon_series ADD CONSTRAINT FK_2218EC0E5278319C FOREIGN KEY (series_id) REFERENCES series (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sermon_speaker ADD CONSTRAINT FK_D9FAA2FE212AE469 FOREIGN KEY (sermon_id) REFERENCES sermon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sermon_speaker ADD CONSTRAINT FK_D9FAA2FED04A0F27 FOREIGN KEY (speaker_id) REFERENCES speaker (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sermon_series DROP FOREIGN KEY FK_2218EC0E5278319C');
        $this->addSql('ALTER TABLE sermon_series DROP FOREIGN KEY FK_2218EC0E212AE469');
        $this->addSql('ALTER TABLE sermon_speaker DROP FOREIGN KEY FK_D9FAA2FE212AE469');
        $this->addSql('ALTER TABLE sermon_speaker DROP FOREIGN KEY FK_D9FAA2FED04A0F27');
        $this->addSql('ALTER TABLE sermon DROP FOREIGN KEY FK_A4F02DA2DBF4AE6F');
        $this->addSql('DROP TABLE public_sermon');
        $this->addSql('DROP TABLE series');
        $this->addSql('DROP TABLE sermon');
        $this->addSql('DROP TABLE sermon_series');
        $this->addSql('DROP TABLE sermon_speaker');
        $this->addSql('DROP TABLE speaker');
        $this->addSql('DROP TABLE apm');
    }
}
