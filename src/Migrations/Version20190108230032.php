<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190108230032 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE question_series (id INT AUTO_INCREMENT NOT NULL, question_qa_id INT NOT NULL, name VARCHAR(255) NOT NULL, current TINYINT(1) NOT NULL, INDEX IDX_3D4D2E2528E1A322 (question_qa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_qa (id INT AUTO_INCREMENT NOT NULL, number INT NOT NULL, question VARCHAR(255) NOT NULL, answer VARCHAR(255) NOT NULL, publish_date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE question_series ADD CONSTRAINT FK_3D4D2E2528E1A322 FOREIGN KEY (question_qa_id) REFERENCES question_qa (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE question_series DROP FOREIGN KEY FK_3D4D2E2528E1A322');
        $this->addSql('DROP TABLE question_series');
        $this->addSql('DROP TABLE question_qa');
    }
}
