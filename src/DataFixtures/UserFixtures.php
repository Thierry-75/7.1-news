<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Avatar;
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
            $avatar = new Avatar();
            $user->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER'])
                ->setCreateAt(new \DateTimeImmutable())
                ->setPlainpassword('ArethiA75!')
                ->setZip(str_replace(' ', '', $this->faker->postcode()))
                ->setCity($this->faker->city())
                ->setPseudo(mt_rand(0, 1) === 1 ? $this->faker->firstNameFemale() . '-' . mt_rand(1, 99) : $this->faker->firstNameMale() . '-' . mt_rand(0, 99))
                ->setIsVerified(mt_rand(0, 1) === 1 ? true : false);
                $avatar->setName('a1acf8e488570e1be12f51fc204859b6.webp');
            $user->setAvatar($avatar);
            $users[] = $user;
            $manager->persist($user);
        }

        $user = new User();
        $avatar = new Avatar();
        $user->setPseudo('user')
        ->setEmail('user@demo.fr')
        ->setRoles(['ROLE_USER'])
        ->setIsVerified(true)
        ->setCreateAt(new \DateTimeImmutable())
        ->setZip('75010')
        ->setCity('Paris')
        ->setCreateAt(new \DateTimeImmutable())
        ->setPlainpassword('ArethiA75!');
        $avatar->setName('a1acf8e488570e1be12f51fc204859b6.webp');
        $user->setAvatar($avatar);
        $this->addReference('user', $user);

        $manager->persist($user);

        $admin = new User();
        $avatar = new Avatar();
        $admin->setPseudo('admin')
            ->setEmail('admin@demo.fr')
            ->setRoles(['ROLE_USER','ROLE_ADMIN'])
            ->setIsVerified(true)
            ->setCreateAt(new \DateTimeImmutable())
            ->setZip('75010')
            ->setCity('Paris')
            ->setCreateAt(new \DateTimeImmutable())
            ->setPlainpassword('ArethiA75!');
            $avatar->setName('a1acf8e488570e1be12f51fc204859b6.webp');
            $user->setAvatar($avatar);
        $this->addReference('admin', $admin);

        $manager->persist($admin);

        $manager->flush();
    }
}
