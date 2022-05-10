<?php

namespace App\Command;

use App\DataFixtures\AttachmentMetadataTypeFixtures;
use App\DataFixtures\BibleBooksFixtures;
use App\DataFixtures\UsersFixture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitCommand extends Command
{
    protected static $defaultName = 'app:init';
    protected static $defaultDescription = 'Creates seed data such as bible books';

    private EntityManagerInterface $entityManager;
    private array $fixtures = [];

    public function __construct(EntityManagerInterface $entityManager, AttachmentMetadataTypeFixtures $attachmentMetadataTypeFixtures, BibleBooksFixtures $bibleBooksFixtures, UsersFixture $usersFixture = null, string $name = null)
    {
        parent::__construct($name);
        $this->entityManager = $entityManager;
        $this->fixtures = [
            $attachmentMetadataTypeFixtures,
            $bibleBooksFixtures,
            $usersFixture,
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
