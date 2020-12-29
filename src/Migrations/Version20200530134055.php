<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200530134055 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE public_sermon ADD event_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE public_sermon ADD CONSTRAINT FK_A401821D71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A401821D71F7E88B ON public_sermon (event_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE public_sermon DROP FOREIGN KEY FK_A401821D71F7E88B');
        $this->addSql('DROP INDEX UNIQ_A401821D71F7E88B ON public_sermon');
        $this->addSql('ALTER TABLE public_sermon DROP event_id');
    }
}
