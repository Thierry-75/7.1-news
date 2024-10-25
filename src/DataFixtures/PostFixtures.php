<?php

namespace App\DataFixtures;


use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $post = new Article();
        $post->setTitre('Ceci est un titre')
            ->setSlug(strtolower($this->slugger->slug($post->getTitre())))
            ->setContenu('Ceci est le contenu')
            ->setUser($this->getReference('admin'))
            ->setFeaturedImage('default.webp')
            ->setCreateAt(new \DateTimeImmutable());

        $manager->persist($post);
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