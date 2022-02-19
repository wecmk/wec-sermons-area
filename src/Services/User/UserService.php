<?php

namespace App\Services\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/*
 * @author Samuel Pearce <samuel.pearce@open.ac.uk>
 */

class UserService
{
    private LoggerInterface $logger;

    private UserRepository $repository;

    private EntityManagerInterface $em;

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        LoggerInterface $logger,
        UserRepository $userRepository,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $entityManager
    ) {
        $this->logger = $logger;
        $this->em = $entityManager;
        $this->repository = $userRepository;
        $this->passwordHasher = $hasher;
    }

    /**
     * Returns an array of matching items
     * @param string $name
     * @return array
     */
    public function findBy($username)
    {
        return $this->repository->findBy(['username' => $username]);
    }

    /**
     * Adds a new user
     * @param User $user
     * @return User The managed persisted object
     */
    public function add(User $user)
    {
        // If the password isn't set, let's encode what is set is PlainPassword
        if (empty($user->getPassword())) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPlainPassword()));
        }
        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

    /**
     * Adds a series
     * @param Series $series
     * @return Series The managed persisted object
     */
    public function create($username, $email, $unhashedPassword, $roles = ['ROLE_USER'])
    {
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        if (!in_array("ROLE_USER", $roles)) {
            $roles[] = 'ROLE_USER';
        }
        $user->setRoles($roles);

        $password = $this->passwordHasher->hashPassword($user, $unhashedPassword);
        $user->setPassword($password);
        $this->em->persist($user);
        $this->em->flush();

        # @todo #18 How do we validate a user was created?
    }

    public function promote($username, $role)
    {
        /** @var User $user */
        $user = $this->repository->findOneBy(['username' => $username]);
        $roles = $user->getRoles();
        $roles[] = $role;
        $user->setRoles($roles);
        $this->em->persist($user);
        $this->em->flush();
    }
}
