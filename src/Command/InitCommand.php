<?php

namespace App\Command;

use App\DataFixtures\AttachmentMetadataTypeFixtures;
use App\DataFixtures\BibleBooksFixtures;
use App\DataFixtures\UsersFixture;
use App\DataFixtures\SeriesFixture;
use App\DataFixtures\MessageFixture;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:init',
    description: 'Creates seed data such as bible books.',
)]
class InitCommand extends Command
{

    private EntityManagerInterface $entityManager;
    private array $fixtures = [];

    public function __construct(EntityManagerInterface $entityManager, AttachmentMetadataTypeFixtures $attachmentMetadataTypeFixtures, BibleBooksFixtures $bibleBooksFixtures, SeriesFixture $seriesFixture, MessageFixture $messageFixture, UsersFixture $usersFixture = null, string $name = null)
    {
        parent::__construct($name);
        $this->entityManager = $entityManager;
        $this->fixtures = [
             //$attachmentMetadataTypeFixtures, this is no longer used
             $bibleBooksFixtures,
             $usersFixture,
             $seriesFixture, 
             //$messageFixture, this has old code that might need to be updated
        ];
    }

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->fixtures as $fixture) {
            if ($fixture == null) {
                $output->writeln("Fixture is null");
            } else {
                $output->writeln("processing " . get_class($fixture));
                $fixture->load($this->entityManager);
            }
        }
        return Command::SUCCESS;
    }
}
