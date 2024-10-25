<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostFixtures extends Fixture
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
        $article = new Article();
        $article->setTitre($this->faker->sentence(5));
                $article->setContenu($this->faker->paragraph());
                $article->setSlug(strtolower($this->slugger->slug($article->getTitre())));
                $article->setUser($this->getReference('admin'));
                $article->setFeaturedImage('default.webp');
                $article->setCreateAt(new \DateTimeImmutable());
        $manager->persist($article);
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
