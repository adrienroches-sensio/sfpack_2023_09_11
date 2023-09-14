<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class UserFixtures extends Fixture
{
    private const USERS = [
        [
            'username' => 'adrien',
            'password' => 'adrien',
            'is_admin' => true,
        ],
        [
            'username' => 'max',
            'password' => 'max',
            'is_admin' => false,
        ],
    ];

    public function __construct(
        private readonly PasswordHasherFactoryInterface $passwordHasherFactory,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::USERS as $userDetail) {
            $user = (new User())
                ->setUsername($userDetail['username'])
                ->setPassword($this->passwordHasherFactory->getPasswordHasher(User::class)->hash($userDetail['password']))
            ;

            if (true === $userDetail['is_admin']) {
                $user->setRoles(['ROLE_ADMIN']);
            }

            $manager->persist($user);
        }

        $manager->flush();
    }
}
