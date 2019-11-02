<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Services\User\UserService;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create-user';

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
            ->addArgument('username', InputArgument::REQUIRED, 'Username for the new user')
            ->addArgument('email', InputArgument::REQUIRED, 'Email for the new user')
            ->addArgument('password', InputArgument::REQUIRED, 'Password for the new user')
            # @todo #3 Support Roles when adding users to Symfony
            # @todo #3 Support --super-admin when creating users. See https://stackoverflow.com/a/19678993/2952983
//            ->addOption('roles', null, InputOption::VALUE_IS_ARRAY, 'Additional roles for the user')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $username = $input->getArgument('username');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        $this->userService->create($username, $email, $password);

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }
}
