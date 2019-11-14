<?php

namespace App\Services\User;

use Psr\Log\LoggerInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;

/*
 * @author Samuel Pearce <samuel.pearce@open.ac.uk>
 */

class UserService
{
    /* @var $logger LoggerInterface */

    private $logger;

    /* @var $repository \Doctrine\Common\Persistence\ObjectManager */
    private $repository;
    
    /** @var EntityManagerInterface $em */
    private $em;
    
    /** @var UserPasswordEncoderInterface $encoder */
    private $encoder;
    
    public function __construct(
        LoggerInterface $logger,
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $encoder,
        EntityManagerInterface $entityManager
    ) {
        $this->logger = $logger;
        $this->em = $em;
        $this->repository = $em->getRepository(User::class);
        $this->encoder = $encoder;
    }

    /**
     * Returns an array of matching items
     * @param string $name
     * @return array
     */
    public function findBy($username)
    {
        return $this->repository->findBy(['username' => $name]);
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
            $user->setPassword($this->encoder->encodePassword($user, $user->getPlainPassword()));
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
        $user->setPlainPassword($unhashedPassword);
        $user->setRoles($roles);
        
        $password = $this->encoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);
        $this->em->persist($user);
        $this->em->flush();
        
        # @todo #3 How do we validate a user was created?
    }
}