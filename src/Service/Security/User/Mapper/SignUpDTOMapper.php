<?php

namespace Silecust\WebShop\Service\Security\User\Mapper;

use Silecust\WebShop\Entity\User;
use Silecust\WebShop\Form\Security\User\DTO\SignUpSimpleDTO;
use Silecust\WebShop\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class SignUpDTOMapper
{

    public function __construct(private UserRepository $userRepository,
        private UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    public function mapToEntityForCreate(SignUpSimpleDTO $signUpDTO): User
    {
        $user = $this->userRepository->create();

        $user->setLogin($signUpDTO->login);

        // encode the plain password
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $signUpDTO->password
            )
        );
        $user->setRoles(['ROLE_CUSTOMER']);

        $user->setCreatedAt(new \DateTime());

        $user->setLastLoggedInAt($user->getCreatedAt());

        return $user;
    }


}