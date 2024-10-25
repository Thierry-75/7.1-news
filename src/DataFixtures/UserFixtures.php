<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setPseudo('user')
        ->setEmail('user@demo.fr')
        ->setRoles(['ROLE_USER'])
        ->setIsVerified(true)
        ->setCreateAt(new \DateTimeImmutable())
        ->setZip('75010')
        ->setCity('Paris')
        ->setCreateAt(new \DateTimeImmutable())
        ->setPlainpassword('ArethiA75!');
        $this->addReference('user', $user);

        $manager->persist($user);

        $admin = new User();
        $admin->setPseudo('admin')
            ->setEmail('admin@demo.fr')
            ->setRoles(['ROLE_USER','ROLE_ADMIN'])
            ->setIsVerified(true)
            ->setCreateAt(new \DateTimeImmutable())
            ->setZip('75010')
            ->setCity('Paris')
            ->setCreateAt(new \DateTimeImmutable())
            ->setPlainpassword('ArethiA75!');
        $this->addReference('admin', $admin);

        $manager->persist($admin);

        $manager->flush();
    }
}
