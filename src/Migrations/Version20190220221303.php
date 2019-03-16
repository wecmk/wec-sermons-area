<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190220221303 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sermon DROP FOREIGN KEY FK_A4F02DA2DBF4AE6F');
        $this->addSql('DROP TABLE apm');
        $this->addSql('ALTER TABLE series ADD deleted_at DATETIME DEFAULT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('DROP INDEX IDX_A4F02DA2DBF4AE6F ON sermon');
        $this->addSql('ALTER TABLE sermon ADD apm VARCHAR(3) NOT NULL, DROP apm_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE apm (id INT AUTO_INCREMENT NOT NULL, apm VARCHAR(3) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE series DROP deleted_at, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE sermon ADD apm_id INT DEFAULT NULL, DROP apm');
        $this->addSql('ALTER TABLE sermon ADD CONSTRAINT FK_A4F02DA2DBF4AE6F FOREIGN KEY (apm_id) REFERENCES apm (id)');
        $this->addSql('CREATE INDEX IDX_A4F02DA2DBF4AE6F ON sermon (apm_id)');
    }
}
