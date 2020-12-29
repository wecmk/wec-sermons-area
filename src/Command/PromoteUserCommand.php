<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Services\User\UserService;

class PromoteUserCommand extends Command
{
    protected static $defaultName = 'app:promote-user';

    /** @var UserService $userService */
    private $userService;
    
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        parent::__construct();
    }

    
    protected function configure()
    {
        $this
            ->setDescription('Creates a new user')
            ->addArgument('username', InputArgument::REQUIRED, 'Username for the user')
            ->addArgument("role", InputArgument::REQUIRED, 'the role to add to the user')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $username = $input->getArgument('username');
        $role = $input->getArgument('role');

        $this->userService->promote($username, $role);

        return $io->success('User promoted: ' . print_r($username, true));
    }
}
