<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public UserPasswordHasherInterface $poasswordHasher;

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        for($i = 0; $i < 10; $i++) {
            $plaintextPassword = 'password' . $i;
            $user = new User();
            $user->setEmail('user' . $i . '@example.com');
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
