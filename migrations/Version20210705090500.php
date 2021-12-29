<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210705090500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds custom id generation for events. Also assigns ids to existing events';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_3BAE0AA7D17F50A6 ON event');
        $this->addSql('ALTER TABLE event ADD short_id INT NOT NULL, DROP uuid');
        // Preseve Tape IDs using the 2xxx range
        $this->addSql('UPDATE event SET short_id = CAST(2000 + REPLACE(`legacy_id`, "TP", "") AS UNSIGNED) WHERE legacy_id LIKE "TP%"');
        // Preseve legacy IDs
        $this->addSql('UPDATE event SET short_id = CAST(`legacy_id` AS UNSIGNED) WHERE short_id = 0 AND LENGTH(`legacy_id`) = 4 AND legacy_id regexp \'[0-9]{4}\'');
        // Set everything else in the 3000 range
        $this->addSql('SET @row_number = 3000; UPDATE event SET short_id = (@row_number:=@row_number + 1) WHERE short_id = 0');
        $this->addSql('UPDATE event SET id = `short_id`');
        $this->addSql('ALTER TABLE event AUTO_INCREMENT=4000');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3BAE0AA7F8496E51 ON event (short_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_3BAE0AA7F8496E51 ON event');
        $this->addSql('ALTER TABLE event ADD uuid CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:uuid)\', DROP short_id, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3BAE0AA7D17F50A6 ON event (uuid)');
    }
}
