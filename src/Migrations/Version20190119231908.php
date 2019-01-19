<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190119231908 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE sermon_speaker');
        $this->addSql('ALTER TABLE sermon ADD speaker_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sermon ADD CONSTRAINT FK_A4F02DA2D04A0F27 FOREIGN KEY (speaker_id) REFERENCES speaker (id)');
        $this->addSql('CREATE INDEX IDX_A4F02DA2D04A0F27 ON sermon (speaker_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sermon_speaker (sermon_id INT NOT NULL, speaker_id INT NOT NULL, INDEX IDX_D9FAA2FE212AE469 (sermon_id), INDEX IDX_D9FAA2FED04A0F27 (speaker_id), PRIMARY KEY(sermon_id, speaker_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE sermon_speaker ADD CONSTRAINT FK_D9FAA2FE212AE469 FOREIGN KEY (sermon_id) REFERENCES sermon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sermon_speaker ADD CONSTRAINT FK_D9FAA2FED04A0F27 FOREIGN KEY (speaker_id) REFERENCES speaker (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sermon DROP FOREIGN KEY FK_A4F02DA2D04A0F27');
        $this->addSql('DROP INDEX IDX_A4F02DA2D04A0F27 ON sermon');
        $this->addSql('ALTER TABLE sermon DROP speaker_id');
    }
}
