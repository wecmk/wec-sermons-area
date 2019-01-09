<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190108232624 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE question_series DROP FOREIGN KEY FK_3D4D2E2528E1A322');
        $this->addSql('DROP INDEX IDX_3D4D2E2528E1A322 ON question_series');
        $this->addSql('ALTER TABLE question_series DROP question_qa_id');
        $this->addSql('ALTER TABLE question_qa ADD question_series_id INT NOT NULL');
        $this->addSql('ALTER TABLE question_qa ADD CONSTRAINT FK_EC569A695BAD7507 FOREIGN KEY (question_series_id) REFERENCES question_series (id)');
        $this->addSql('CREATE INDEX IDX_EC569A695BAD7507 ON question_qa (question_series_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE question_qa DROP FOREIGN KEY FK_EC569A695BAD7507');
        $this->addSql('DROP INDEX IDX_EC569A695BAD7507 ON question_qa');
        $this->addSql('ALTER TABLE question_qa DROP question_series_id');
        $this->addSql('ALTER TABLE question_series ADD question_qa_id INT NOT NULL');
        $this->addSql('ALTER TABLE question_series ADD CONSTRAINT FK_3D4D2E2528E1A322 FOREIGN KEY (question_qa_id) REFERENCES question_qa (id)');
        $this->addSql('CREATE INDEX IDX_3D4D2E2528E1A322 ON question_series (question_qa_id)');
    }
}
