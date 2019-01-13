<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190113223715 extends AbstractMigration {

    public function up(Schema $schema): void {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("INSERT INTO `bible_books` (`id`, `book`, `sort`) VALUES
            (1, 'Genesis', 1),
            (2, 'Exodus', 2),
            (3, 'Leviticus', 3),
            (4, 'Numbers', 4),
            (5, 'Deuteronomy', 5),
            (6, 'Joshua', 6),
            (7, 'Judges', 7),
            (8, 'Ruth', 8),
            (9, '1 Samuel', 9),
            (10, '2 Samuel', 10),
            (11, '1 Kings', 11),
            (12, '2 Kings', 12),
            (13, '1 Chronicles', 13),
            (14, '2 Chronicles', 14),
            (15, 'Ezra', 15),
            (16, 'Nehemiah', 16),
            (17, 'Esther', 17),
            (18, 'Job', 18),
            (19, 'Psalm', 19),
            (20, 'Proverbs', 20),
            (21, 'Ecclesiastes', 21),
            (22, 'Song of Solomon', 22),
            (23, 'Isaiah', 23),
            (24, 'Jeremiah', 24),
            (25, 'Lamentations', 25),
            (26, 'Ezekiel', 26),
            (27, 'Daniel', 27),
            (28, 'Hosea', 28),
            (29, 'Joel', 29),
            (30, 'Amos', 30),
            (31, 'Obadiah', 31),
            (32, 'Jonah', 32),
            (33, 'Micah', 33),
            (34, 'Nahum', 34),
            (35, 'Habakkuk', 35),
            (36, 'Zephaniah', 36),
            (37, 'Haggai', 37),
            (38, 'Zechariah', 38),
            (39, 'Malachi', 39),
            (40, 'Matthew', 40),
            (41, 'Mark', 41),
            (42, 'Luke', 42),
            (43, 'John', 43),
            (44, 'Acts', 44),
            (45, 'Romans', 45),
            (46, '1 Corinthians', 46),
            (47, '2 Corinthians', 47),
            (48, 'Galatians', 48),
            (49, 'Ephesians', 49),
            (50, 'Philippians', 50),
            (51, 'Colossians', 51),
            (52, '1 Thessalonians', 52),
            (53, '2 Thessalonians', 53),
            (54, '1 Timothy', 54),
            (55, '2 Timothy', 55),
            (56, 'Titus', 56),
            (57, 'Philemon', 57),
            (58, 'Hebrews', 58),
            (59, 'James', 59),
            (60, '1 Peter', 60),
            (61, '2 Peter', 61),
            (62, '1 John', 62),
            (63, '2 John', 63),
            (64, '3 John', 64),
            (65, 'Jude', 65),
            (66, 'Revelation', 66);");
    }

    public function down(Schema $schema): void {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('TRUNCATE TABLE `bible_books`');
    }

}
