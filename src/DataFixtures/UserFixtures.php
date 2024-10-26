<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {

        for ($i = 0; $i < 3; $i++) {
            $user  = new User();
            $user->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER'])
                ->setCreateAt(new \DateTimeImmutable())
                ->setPlainpassword('ArethiA75!')
                ->setZip(str_replace(' ', '', $this->faker->postcode()))
                ->setCity($this->faker->city())
                ->setPseudo(mt_rand(0, 1) === 1 ? $this->faker->firstNameFemale() . '-' . mt_rand(1, 99) : $this->faker->firstNameMale() . '-' . mt_rand(0, 99))
                ->setIsVerified(mt_rand(0, 1) === 1 ? true : false);
            $users[] = $user;
            $manager->persist($user);
        }

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
