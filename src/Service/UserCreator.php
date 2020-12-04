<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserCreator
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var string
     */
    private $error;

    public function __construct(
        UserPasswordEncoderInterface $userPasswordEncoder,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    public function create(string $username, string $password): User
    {
        $user = $this->userRepository->findOneBy(['login' => $username]);
        if ($user !== null) {
            $this->error = 'User already exists';
            return $user;
        }

        $user = new User();
        $user->setLogin($username);
        $user->setPassword($this->userPasswordEncoder->encodePassword($user, $password));
        $user->setApiKey(\uniqid('', true));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function getError(): string
    {
        return $this->error;
    }
}
