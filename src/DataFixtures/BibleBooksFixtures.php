<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class BibleBooksFixtures extends Fixture implements \Doctrine\Bundle\FixturesBundle\FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $books = array(
            1 => array('Genesis', 'Gen', 'Ge', 'Gn'),
            2 => array('Exodus', 'Exo', 'Ex', 'Exod'),
            3 => array('Leviticus', 'Lev', 'Le', 'Lv'),
            4 => array('Numbers', 'Num', 'Nu', 'Nm', 'Nb'),
            5 => array('Deuteronomy', 'Deut', 'Dt'),
            6 => array('Joshua', 'Josh', 'Jos', 'Jsh'),
            7 => array('Judges', 'Judg', 'Jdg', 'Jg', 'Jdgs'),
            8 => array('Ruth', 'Rth', 'Ru'),
            9 => array('1 Samuel', '1 Sam', '1 Sa', '1Samuel', '1S', 'I Sa', '1 Sm', '1Sa', 'I Sam', '1Sam', 'I Samuel', '1st Samuel', 'First Samuel'),
            10 => array('2 Samuel', '2 Sam', '2 Sa', '2S', 'II Sa', '2 Sm', '2Sa', 'II Sam', '2Sam', 'II Samuel', '2Samuel', '2nd Samuel', 'Second Samuel'),
            11 => array('1 Kings', '1 Kgs', '1 Ki', '1K', 'I Kgs', '1Kgs', 'I Ki', '1Ki', 'I Kings', '1Kings', '1st Kgs', '1st Kings', 'First Kings', 'First Kgs', '1Kin'),
            12 => array('2 Kings', '2 Kgs', '2 Ki', '2K', 'II Kgs', '2Kgs', 'II Ki', '2Ki', 'II Kings', '2Kings', '2nd Kgs', '2nd Kings', 'Second Kings', 'Second Kgs', '2Kin'),
            13 => array('1 Chronicles', '1 Chron', '1 Ch', 'I Ch', '1Ch', '1 Chr', 'I Chr', '1Chr', 'I Chron', '1Chron', 'I Chronicles', '1Chronicles', '1st Chronicles', 'First Chronicles'),
            14 => array('2 Chronicles', '2 Chron', '2 Ch', 'II Ch', '2Ch', 'II Chr', '2Chr', 'II Chron', '2Chron', 'II Chronicles', '2Chronicles', '2nd Chronicles', 'Second Chronicles'),
            15 => array('Ezra', 'Ezra', 'Ezr'),
            16 => array('Nehemiah', 'Neh', 'Ne'),
            17 => array('Esther', 'Esth', 'Es'),
            18 => array('Job', 'Job', 'Job', 'Jb'),
            19 => array('Psalm', 'Pslm', 'Ps', 'Psalms', 'Psa', 'Psm', 'Pss'),
            20 => array('Proverbs', 'Prov', 'Pr', 'Prv'),
            21 => array('Ecclesiastes', 'Eccles', 'Ec', 'Ecc', 'Qoh', 'Qoheleth'),
            22 => array('Song of Solomon', 'Song', 'So', 'Canticle of Canticles', 'Canticles', 'Song of Songs', 'SOS'),
            23 => array('Isaiah', 'Isa', 'Is'),
            24 => array('Jeremiah', 'Jer', 'Je', 'Jr'),
            25 => array('Lamentations', 'Lam', 'La'),
            26 => array('Ezekiel', 'Ezek', 'Eze', 'Ezk'),
            27 => array('Daniel', 'Dan', 'Da', 'Dn'),
            28 => array('Hosea', 'Hos', 'Ho'),
            29 => array('Joel', 'Joel', 'Joe', 'Jl'),
            30 => array('Amos', 'Amos', 'Am'),
            31 => array('Obadiah', 'Obad', 'Ob'),
            32 => array('Jonah', 'Jnh', 'Jon'),
            33 => array('Micah', 'Micah', 'Mic'),
            34 => array('Nahum', 'Nah', 'Na'),
            35 => array('Habakkuk', 'Hab', 'Hab'),
            36 => array('Zephaniah', 'Zeph', 'Zep', 'Zp'),
            37 => array('Haggai', 'Haggai', 'Hag', 'Hg'),
            38 => array('Zechariah', 'Zech', 'Zec', 'Zc'),
            39 => array('Malachi', 'Mal', 'Mal', 'Ml'),
            40 => array('Matthew', 'Matt', 'Mt'),
            41 => array('Mark', 'Mrk', 'Mk', 'Mr'),
            42 => array('Luke', 'Luk', 'Lk'),
            43 => array('John', 'John', 'Jn', 'Jhn'),
            44 => array('Acts', 'Acts', 'Ac'),
            45 => array('Romans', 'Rom', 'Ro', 'Rm'),
            46 => array('1 Corinthians', '1 Cor', '1 Co', 'I Co', '1Co', 'I Cor', '1Cor', 'I Corinthians', '1Corinthians', '1st Corinthians', 'First Corinthians'),
            47 => array('2 Corinthians', '2 Cor', '2 Co', 'II Co', '2Co', 'II Cor', '2Cor', 'II Corinthians', '2Corinthians', '2nd Corinthians', 'Second Corinthians'),
            48 => array('Galatians', 'Gal', 'Ga'),
            49 => array('Ephesians', 'Ephes', 'Eph'),
            50 => array('Philippians', 'Phil', 'Php'),
            51 => array('Colossians', 'Col', 'Col'),
            52 => array('1 Thessalonians', '1 Thess', '1 Th', 'I Th', '1Th', 'I Thes', '1Thes', 'I Thess', '1Thess', 'I Thessalonians', '1Thessalonians', '1st Thessalonians', 'First Thessalonians'),
            53 => array('2 Thessalonians', '2 Thess', '2 Th', 'II Th', '2Th', 'II Thes', '2Thes', 'II Thess', '2Thess', 'II Thessalonians', '2Thessalonians', '2nd Thessalonians', 'Second Thessalonians'),
            54 => array('1 Timothy', '1 Tim', '1 Ti', 'I Ti', '1Ti', 'I Tim', '1Tim', 'I Timothy', '1Timothy', '1st Timothy', 'First Timothy'),
            55 => array('2 Timothy', '2 Tim', '2 Ti', 'II Ti', '2Ti', 'II Tim', '2Tim', 'II Timothy', '2Timothy', '2nd Timothy', 'Second Timothy'),
            56 => array('Titus', 'Titus', 'Tit'),
            57 => array('Philemon', 'Philem', 'Phm'),
            58 => array('Hebrews', 'Hebrews', 'Heb'),
            59 => array('James', 'James', 'Jas', 'Jm'),
            60 => array('1 Peter', '1 Pet', '1 Pe', 'I Pe', '1Pe', 'I Pet', '1Pet', 'I Pt', '1 Pt', '1Pt', 'I Peter', '1Peter', '1st Peter', 'First Peter'),
            61 => array('2 Peter', '2 Pet', '2 Pe', 'II Pe', '2Pe', 'II Pet', '2Pet', 'II Pt', '2 Pt', '2Pt', 'II Peter', '2Peter', '2nd Peter', 'Second Peter'),
            62 => array('1 John', '1 John', '1 Jn', 'I Jn', '1Jn', 'I Jo', '1Jo', 'I Joh', '1Joh', 'I Jhn', '1 Jhn', '1Jhn', 'I John', '1John', '1st John', 'First John'),
            63 => array('2 John', '2 John', '2 Jn', 'II Jn', '2Jn', 'II Jo', '2Jo', 'II Joh', '2Joh', 'II Jhn', '2 Jhn', '2Jhn', 'II John', '2John', '2nd John', 'Second John'),
            64 => array('3 John', '3 John', '3 Jn', 'III Jn', '3Jn', 'III Jo', '3Jo', 'III Joh', '3Joh', 'III Jhn', '3 Jhn', '3Jhn', 'III John', '3John', '3rd John', 'Third John'),
            65 => array('Jude', 'Jude', 'Jud'),
            66 => array('Revelation', 'Rev', 'Re', 'The Revelation')
        );
        foreach ($books as $key => $value) {
            $book = new \App\Entity\BibleBooks();
            $book->setBook($value[0]);
            $book->setSort($key);
            $manager->persist($book);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['prod', 'dev', 'test'];
    }
}
