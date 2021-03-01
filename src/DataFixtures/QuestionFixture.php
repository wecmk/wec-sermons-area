<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class QuestionFixture extends Fixture implements \Doctrine\Bundle\FixturesBundle\FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $questions = array(
            "1" => 'God',
            "2" => 'Man, Sin and Salvation',
            "3" => 'The Experience of Salvation',
            "4" => 'The Church',
            "5" => 'The Future',
        );

        $questionAnswers = array(
            array(
                "series" => "1",
                "number" => "1",
                "question" => "Why do some people say there is no God?",
                "answer" => "People say there is no God because they do not want Him, preferring to live their own way.",
            ),
            array(
                "series" => "1",
                "number" => "2",
                "question" => "Can we prove that God exists?",
                "answer" => "We cannot prove that God exists; we do not need to. It is clear that He does.",
            ),
            array(
                "series" => "1",
                "number" => "3",
                "question" => "How do we know that God exists?",
                "answer" => "We know that God exists because we have a sense of God within us, and because we can see creation around us.",
            ),
            array(
                "series" => "1",
                "number" => "4",
                "question" => "Has God shown Himself in any other way?",
                "answer" => "God has shown Himself most clearly in the Bible, which is His word.",
            ),
            array(
                "series" => "1",
                "number" => "5",
                "question" => "Who wrote the Bible?",
                "answer" => "The Bible was written by men, guided by the Holy Spirit.",
            ),
            array(
                "series" => "1",
                "number" => "6",
                "question" => "How can we understand the Bible?",
                "answer" => "We can understand the Bible only with the help of the Holy Spirit.",
            ),
            array(
                "series" => "1",
                "number" => "7",
                "question" => "What does the Bible tell us?",
                "answer" => "The Bible tells us about God and His salvation.",
            ),
            array(
                "series" => "1",
                "number" => "8",
                "question" => "What is God like?",
                "answer" => "God is majestic, invisible, holy and good.",
            ),
            array(
                "series" => "1",
                "number" => "9",
                "question" => "Is God one person?",
                "answer" => "God is one, existing in three persons: Father, Son and Holy Spirit.",
            ),
            array(
                "series" => "1",
                "number" => "10",
                "question" => "Did God make the world?",
                "answer" => "God made all things good, and man in His own image.",
            ),
            array(
                "series" => "1",
                "number" => "11",
                "question" => "What does God do for the world?",
                "answer" => "God controls all things, and especially provides salvation.",
            ),
            array(
                "series" => "2",
                "number" => "1",
                "question" => "What were Adam and Eve like?",
                "answer" => "Adam and Eve were made righteous and happy.",
            ),
            array(
                "series" => "2",
                "number" => "2",
                "question" => "Did Adam and Eve continue to obey God?",
                "answer" => "Adam and Eve rebelled against God.",
            ),
            array(
                "series" => "2",
                "number" => "3",
                "question" => "Does Adam’s sin affect all people?",
                "answer" => "Because Adam sinned, everyone is born a sinner.",
            ),
            array(
                "series" => "2",
                "number" => "4",
                "question" => "Do people obey God now?",
                "answer" => "No-one wants to obey God; no-one can.",
            ),
            array(
                "series" => "2",
                "number" => "5",
                "question" => "Why is sin so serious?",
                "answer" => "Sin brings God’s judgement against us.",
            ),
            array(
                "series" => "2",
                "number" => "6",
                "question" => "What did God plan to do for sinners?",
                "answer" => "In love, God planned to send His Son to save sinners.",
            ),
            array(
                "series" => "2",
                "number" => "7",
                "question" => "When did God make His plan to save sinners?",
                "answer" => "God made a plan to save us before the world was made.",
            ),
            array(
                "series" => "2",
                "number" => "8",
                "question" => "How did God’s Son come into the world?",
                "answer" => "God’s Son became a man and was born of Mary.",
            ),
            array(
                "series" => "2",
                "number" => "9",
                "question" => "How did Jesus Christ save sinners?",
                "answer" => "Christ saved sinners by living a perfect life and dying on a cross.",
            ),
            array(
                "series" => "2",
                "number" => "10",
                "question" => "Do we know that the Lord Jesus did save those He died for?",
                "answer" => "We know that Christ saved those He died for because He rose again and ascended into heaven.",
            ),
            array(
                "series" => "2",
                "number" => "11",
                "question" => "Where is the Lord Jesus Christ now?",
                "answer" => "The Lord Jesus Christ is now in heaven, ruling over all and praying for His people.",
            ),
            array(
                "series" => "3",
                "number" => "1",
                "question" => "How is salvation given to us?",
                "answer" => "Salvation is given to us by the work of the Holy Spirit.",
            ),
            array(
                "series" => "3",
                "number" => "2",
                "question" => "What does the Holy Spirit do in us?",
                "answer" => "The Holy Spirit convicts of sin, gives understanding of God’s Word and enables us to believe in Jesus.",
            ),
            array(
                "series" => "3",
                "number" => "3",
                "question" => "What must we do to be saved?",
                "answer" => "To be saved we must repent of our sin and believe in the Lord Jesus.",
            ),
            array(
                "series" => "3",
                "number" => "4",
                "question" => "What happens the moment we believe?",
                "answer" => "The moment we believe, God declares us righteous and adopts us into His family.",
            ),
            array(
                "series" => "3",
                "number" => "5",
                "question" => "How can we know we are saved?",
                "answer" => "We know we are saved by the presence of the Holy Spirit in our lives.",
            ),
            array(
                "series" => "3",
                "number" => "6",
                "question" => "What does it mean to have new life?",
                "answer" => "To have new life means the power of sin is broken and the Holy Spirit helps us.",
            ),
            array(
                "series" => "3",
                "number" => "7",
                "question" => "How does God want us to live?",
                "answer" => "God wants us to live like Jesus, keeping His laws, and doing good works because we love Him.",
            ),
            array(
                "series" => "3",
                "number" => "8",
                "question" => "What can we do for God?",
                "answer" => "We serve God when we worship Him and when we witness to others.",
            ),
            array(
                "series" => "3",
                "number" => "9",
                "question" => "Why do we still sin?",
                "answer" => "We still sin because we have sinful natures and Satan tempts us.",
            ),
            array(
                "series" => "3",
                "number" => "10",
                "question" => "Will God forgive us when we sin?",
                "answer" => "God does forgive all our sins, but we ought to confess them to Him.",
            ),
            array(
                "series" => "3",
                "number" => "11",
                "question" => "Can believers lose their salvation?",
                "answer" => "Believers cannot lose their salvation; God has promised to keep them forever.",
            ),
            array(
                "series" => "4",
                "number" => "1",
                "question" => "What is the church?
",
                "answer" => "The church is the community of all true believers from every country and every generation with Christ as the Head.",
            ),
            array(
                "series" => "4",
                "number" => "2",
                "question" => "What is the local church?",
                "answer" => "The local church is the community of believers meeting in one place.",
            ),
            array(
                "series" => "4",
                "number" => "3",
                "question" => "Who manages the affairs of the local church?",
                "answer" => "Elders and deacons manage the affairs of the local church.",
            ),
            array(
                "series" => "4",
                "number" => "4",
                "question" => "What two special ordinances did Christ give to the churches?",
                "answer" => "Christ gave the church the two ordinances of baptism and the Lord’s Supper.",
            ),
            array(
                "series" => "4",
                "number" => "5",
                "question" => "What is baptism?",
                "answer" => "Baptism is the dipping of believers into water as a sign that God has saved them.",
            ),
            array(
                "series" => "4",
                "number" => "6",
                "question" => "What is the Lord’s Supper?",
                "answer" => "The Lord’s Supper is the eating of bread and drinking of wine to remember Jesus’ death.",
            ),
            array(
                "series" => "4",
                "number" => "7",
                "question" => "What must take place in the church?",
                "answer" => "There must be worship and prayer, preaching and fellowship in every church.",
            ),
            array(
                "series" => "4",
                "number" => "8",
                "question" => "What are the responsibilities of church members?",
                "answer" => "Church members should attend the meetings and care for each other.",
            ),
            array(
                "series" => "4",
                "number" => "9",
                "question" => "Why do we have special church members meetings?",
                "answer" => "Church members meetings are needed to appoint new officers, to administer church discipline and to welcome new members.",
            ),
            array(
                "series" => "4",
                "number" => "10",
                "question" => "What does the church do for its members?",
                "answer" => "The church is the community through which Christians grow in their faith.",
            ),
            array(
                "series" => "4",
                "number" => "11",
                "question" => "What must the church do for the world?",
                "answer" => "The church must make sure that the gospel is spoken to people everywhere.",
            ),
            array(
                "series" => "5",
                "number" => "1",
                "question" => "What happens when a person dies?",
                "answer" => "When a person dies the body returns to the dust and the soul lives on.",
            ),
            array(
                "series" => "5",
                "number" => "2",
                "question" => "Where do the souls of believers go at death?",
                "answer" => "The souls of believers go to be with Christ at death.",
            ),
            array(
                "series" => "5",
                "number" => "3",
                "question" => "Where do the souls of unbelievers go at death?",
                "answer" => "The souls of unbelievers go to hell at death.",
            ),
            array(
                "series" => "5",
                "number" => "4",
                "question" => "What will happen to people’s bodies?",
                "answer" => "Everyone’s body will be raised from the grave when Jesus comes.",
            ),
            array(
                "series" => "5",
                "number" => "5",
                "question" => "Will Jesus Christ come back again?",
                "answer" => "Jesus Christ will come back again in power and glory to judge the world",
            ),
            array(
                "series" => "5",
                "number" => "6",
                "question" => "What will happen to the universe?",
                "answer" => "This present world will be destroyed, and God will create a   new heavens and earth.",
            ),
            array(
                "series" => "5",
                "number" => "7",
                "question" => "Will Satan be destroyed?",
                "answer" => "Satan will be cast into hell forever.",
            ),
            array(
                "series" => "5",
                "number" => "8",
                "question" => "What will happen to unbelievers at the judgement?",
                "answer" => " At the judgement, unbelievers will be cast into hell, body and soul.",
            ),
            array(
                "series" => "5",
                "number" => "9",
                "question" => "What will happen to believers at the judgement?",
                "answer" => "At the judgement, believers will be blessed, body and soul and will live forever with Christ in the new creation.",
            ),
        );

        foreach ($questions as $qKey => $value) {
            $questionSeries = new \App\Entity\QuestionsAndAnswersSeries();
            $questionSeries->setName($value);
            $questionSeries->setNumber($qKey);
            $questionSeries->setCurrent(false);
            $manager->persist($questionSeries);

            foreach ($questionAnswers as $key => $value) {
                // We can't ensure that the series ID is correct
                // What we do instead is ensure the series number in the arrays
                //   are correct and then reuse the created QuestionSeries
                //   entity above in the setQuestionSeries below
                if ($value['series'] == $qKey) {
                    $qa = new \App\Entity\QuestionsAndAnswers();
                    $qa->setQuestionsAndAnswersSeries($questionSeries);
                    $qa->setNumber($value['number']);
                    $qa->setQuestion($value['question']);
                    $qa->setAnswer($value['answer']);
                    $date = new \DateTime();
                    $date->setDate(2000, 01, 01);
                    $qa->setPublishDate($date);
                    $manager->persist($qa);
                }
            }
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['prod', 'dev', 'test'];
    }
}
