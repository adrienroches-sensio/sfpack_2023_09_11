<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Psr\Clock\ClockInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class UserFixtures extends Fixture
{
    private const USERS = [
        [
            'username' => 'adrien',
            'password' => 'adrien',
            'is_admin' => true,
            'birthdate' => '10 July',
            'age' => 35,
        ],
        [
            'username' => 'max',
            'password' => 'max',
            'is_admin' => true,
            'birthdate' => '5 April',
            'age' => 14,
        ],
        [
            'username' => 'lou',
            'password' => 'lou',
            'is_admin' => false,
            'birthdate' => '22 Dec',
            'age' => 5,
        ],
        [
            'username' => 'john',
            'password' => 'john',
            'is_admin' => false,
            'birthdate' => null,
            'age' => null,
        ],
    ];

    public function __construct(
        private readonly PasswordHasherFactoryInterface $passwordHasherFactory,
        private readonly ClockInterface $clock,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::USERS as $userDetail) {
            $user = (new User())
                ->setUsername($userDetail['username'])
                ->setPassword($this->passwordHasherFactory->getPasswordHasher(User::class)->hash($userDetail['password']))
            ;

            if (null !== $userDetail['age']) {
                $birthYear = $this->clock->now()->modify("- {$userDetail['age']} years")->format('Y');
                $birthDate = new DateTimeImmutable("{$userDetail['birthdate']} {$birthYear}");
                $user->setBirthdate($birthDate);
            }

            if (true === $userDetail['is_admin']) {
                $user->setRoles(['ROLE_ADMIN']);
            }

            $manager->persist($user);
        }

        $manager->flush();
    }
}
