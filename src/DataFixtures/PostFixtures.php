<?php

namespace App\DataFixtures;


use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;
use Faker\Generator;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    private SluggerInterface $slugger;
    private Generator $faker;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        for($i=0; $i < 24; $i++){
        $post = new Article();
        $post->setTitre($this->faker->sentence())
            ->setSlug(strtolower($this->slugger->slug($post->getTitre())))
            ->setContenu($this->faker->text(1000))
            ->setUser($this->getReference('admin'))
            ->setFeaturedImage('default.webp')
            ->setCreateAt(new \DateTimeImmutable());

        $manager->persist($post);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            CategoryFixtures::class,
        ];
    }
}