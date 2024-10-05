<?php

namespace App\Command;

use App\Services\User\UserService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:user',
    description: 'Modify Users.',
)]
class UserCommand extends Command
{
    public function __construct(private readonly UserService $userService, string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('user', InputArgument::REQUIRED, "the username of the user to have an action applied")
            ->addArgument('action', InputArgument::REQUIRED, 'The Action to be taken')
            ->addOption('add-role', null, InputOption::VALUE_REQUIRED, 'The role to be added')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $user = $input->getArgument('user');

        if ($user) {
            $io->note(sprintf('You passed an argument: %s', $user));
            $user = $this->userService->findBy($user);
        }

        $action = $input->getArgument('action');

        if ($action) {
            $io->note(sprintf('You passed an argument: %s', $action));
        }

        try {
            $roleToAdd = $input->getOption("add-role");
            $this->userService->promote($user, $roleToAdd);
            $io->success("User " . $user . "promoted to " . $roleToAdd);
        } catch (InvalidArgumentException $e) {
            $io->warning("invalid argument" . $e);
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
