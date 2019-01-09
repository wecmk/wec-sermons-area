<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190108232625 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

      
        $this->addSql('INSERT INTO `question_series` (`id`, `name`, `current`) VALUES
(1, \'God\', 0),
(2, \'Man, Sin and Salvation\', 0),
(3, \'The Experience of Salvation\', 1),
(4, \'The Church\', 0),
(5, \'The Future\', 0);');

        $this->addSql("INSERT INTO `question_qa` (`id`, `question_series_id`, `number`, `question`, `answer`, `publish_date`) VALUES
(1, 1, 1, 'Why do some people say there is no God?', 'People say there is no God because they do not want Him, preferring to live their own way.', '2011-01-01 00:00:00'),
(2, 1, 2, 'Can we prove that God exists?', 'We cannot prove that God exists; we do not need to. It is clear that He does.', '2011-01-01 00:00:00'),
(3, 1, 3, 'How do we know that God exists?', 'We know that God exists because we have a sense of God within us, and because we can see creation around us.', '2011-01-01 00:00:00'),
(4, 1, 4, 'Has God shown Himself in any other way?', 'God has shown Himself most clearly in the Bible, which is His word.', '2011-01-01 00:00:00'),
(5, 1, 5, 'Who wrote the Bible?', 'The Bible was written by men, guided by the Holy Spirit.', '2011-01-01 00:00:00'),
(6, 1, 6, 'How can we understand the Bible?', 'We can understand the Bible only with the help of the Holy Spirit.', '2011-01-01 00:00:00'),
(7, 1, 7, 'What does the Bible tell us?', 'The Bible tells us about God and His salvation.', '2011-01-01 00:00:00'),
(8, 1, 8, 'What is God like?', 'God is majestic, invisible, holy and good.', '2011-01-01 00:00:00'),
(9, 1, 9, 'Is God one person?', 'God is one, existing in three persons: Father, Son and Holy Spirit.', '2011-01-01 00:00:00'),
(10, 1, 10, 'Did God make the world?', 'God made all things good, and man in His own image.', '2011-01-01 00:00:00'),
(11, 1, 11, 'What does God do for the world?', 'God controls all things, and especially provides salvation.', '2015-01-12 23:48:20'),
(12, 2, 1, 'What were Adam and Eve like?', 'Adam and Eve were made righteous and happy.', '2011-01-01 00:00:00'),
(13, 2, 2, 'Did Adam and Eve continue to obey God?', 'Adam and Eve rebelled against God.', '2011-01-01 00:00:00'),
(14, 2, 3, 'Does Adam’s sin affect all people?', 'Because Adam sinned, everyone is born a sinner.', '2011-01-01 00:00:00'),
(15, 2, 4, 'Do people obey God now?', 'No-one wants to obey God; no-one can.', '2011-01-01 00:00:00'),
(16, 2, 5, 'Why is sin so serious?', 'Sin brings God’s judgement against us.', '2011-01-01 00:00:00'),
(17, 2, 6, 'When did God make His plan to save sinners?', 'God made a plan to save us before the world was made.', '2011-01-01 00:00:00'),
(18, 2, 7, 'What did God plan to do for sinners?', 'In love, God planned to send His Son to save sinners.', '2011-01-01 00:00:00'),
(19, 2, 8, 'How did God’s Son come into the world?', 'God’s Son became a man and was born of Mary.', '2011-01-01 00:00:00'),
(20, 2, 9, 'How did Jesus Christ save sinners?', 'Christ saved sinners by living a perfect life and dying on a cross.', '2011-01-01 00:00:00'),
(21, 2, 10, 'Do we know that the Lord Jesus did save those He died for?', 'We know that Christ saved those He died for because He rose again and ascended into heaven.', '2011-01-01 00:00:00'),
(22, 2, 11, 'Where is the Lord Jesus Christ now?', 'The Lord Jesus Christ is now in heaven, ruling over all and praying for His people.', '2011-01-01 00:00:00'),
(23, 3, 1, 'How is salvation given to us?', 'Salvation is given to us by the work of the Holy Spirit.', '2016-01-10 10:45:00'),
(24, 3, 2, 'What does the Holy Spirit do in us?', 'The Holy Spirit convicts of sin, gives understanding of God’s Word and enables us to believe in Jesus.', '2016-01-17 10:45:00'),
(25, 3, 3, 'What must we do to be saved?', 'To be saved we must repent of our sin and believe in the Lord Jesus.', '2016-01-24 10:45:00'),
(26, 3, 4, 'What happens the moment we believe?', 'The moment we believe, God declares us righteous and adopts us into His family.', '2016-01-31 10:45:00'),
(27, 3, 5, 'How can we know we are saved?', 'We know we are saved by the presence of the Holy Spirit in our lives.', '2016-02-07 10:45:00'),
(28, 3, 6, 'What does it mean to have new life?', 'To have new life means the power of sin is broken and the Holy Spirit helps us.', '2016-02-14 10:45:00'),
(29, 3, 7, 'How does God want us to live?', 'God wants us to live like Jesus, keeping His laws, and doing good works because we love Him.', '2016-02-21 10:45:00'),
(30, 3, 8, 'What can we do for God?', 'We serve God when we worship Him and when we witness to others.', '2016-02-28 10:45:00'),
(31, 3, 9, 'Why do we still sin?', 'We still sin because we have sinful natures and Satan tempts us.', '2016-03-06 10:45:00'),
(32, 3, 10, 'Will God forgive us when we sin?', 'God does forgive all our sins, but we ought to confess them to Him.', '2016-03-13 10:45:00'),
(33, 3, 11, 'Can believers lose their salvation?', 'Believers cannot lose their salvation; God has promised to keep them forever.', '2016-03-20 10:45:00'),
(34, 4, 1, 'What is the church?\r\n', 'The church is the community of all true believers from every country and every generation with Christ as the Head.', '2016-01-20 00:00:00'),
(35, 4, 2, 'What is the local church?', 'The local church is the community of believers meeting in one place.', '2016-01-06 00:00:00'),
(36, 4, 3, 'Who manages the affairs of the local church?', 'Elders and deacons manage the affairs of the local church.', '2016-01-14 00:00:00'),
(37, 4, 4, 'What two special ordinances did Christ give to the churches?', 'Christ gave the church the two ordinances of baptism and the Lord’s Supper.', '2016-01-14 00:00:00'),
(38, 4, 5, 'What is baptism?', 'Baptism is the dipping of believers into water as a sign that God has saved them.', '2016-01-14 00:00:00'),
(39, 4, 6, 'What is the Lord’s Supper?', 'The Lord’s Supper is the eating of bread and drinking of wine to remember Jesus’ death.', '2016-01-14 00:00:00'),
(40, 4, 7, 'What must take place in the church?', 'There must be worship and prayer, preaching and fellowship in every church.', '2016-01-14 00:00:00'),
(41, 4, 8, 'What are the responsibilities of church members?', 'Church members should attend the meetings and care for each other.', '2016-01-14 00:00:00'),
(42, 4, 9, 'Why do we have special church members meetings?', 'Church members meetings are needed to appoint new officers, to administer church discipline and to welcome new members.', '2016-01-14 00:00:00'),
(43, 4, 10, 'What does the church do for its members?', 'The church is the community through which Christians grow in their faith.', '2016-01-14 00:00:00'),
(44, 4, 11, 'What must the church do for the world?', 'The church must make sure that the gospel is spoken to people everywhere.', '2016-01-14 00:00:00'),
(45, 5, 1, 'What happens when a person dies?', 'When a person dies the body returns to the dust and the soul lives on.', '2016-01-14 00:00:00'),
(46, 5, 2, 'Where do the souls of believers go at death?', 'The souls of believers go to be with Christ at death.', '2016-01-14 00:00:00'),
(47, 5, 3, 'Where do the souls of unbelievers go at death?', 'The souls of unbelievers go to hell at death.', '2016-01-14 00:00:00'),
(48, 5, 4, 'What will happen to people’s bodies?', 'Everyone’s body will be raised from the grave when Jesus comes.', '2016-01-14 00:00:00'),
(49, 5, 5, 'Will Jesus Christ come back again?', 'Jesus Christ will come back again in power and glory to judge the world', '2016-01-14 00:00:00'),
(50, 5, 6, 'What will happen to the universe?', 'This present world will be destroyed, and God will create a   new heavens and earth.', '2016-01-14 00:00:00'),
(51, 5, 7, 'Will Satan be destroyed?', 'Satan will be cast into hell forever.', '2016-01-14 00:00:00'),
(52, 5, 8, 'What will happen to unbelievers at the judgement?', ' At the judgement, unbelievers will be cast into hell, body and soul.', '2016-01-14 00:00:00'),
(53, 5, 9, 'What will happen to believers at the judgement?', 'At the judgement, believers will be blessed, body and soul and will live forever with Christ in the new creation.', '2016-01-05 00:00:00');");
 
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    }
}
