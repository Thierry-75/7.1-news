<?php

namespace App\DataFixtures;


use App\Entity\Article;
use App\Entity\Photo;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Service\PhotoService;
use Faker\Factory;
use Faker\Generator;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    private SluggerInterface $slugger;
    private Generator $faker;

    public function __construct(SluggerInterface $slugger, PhotoService $photoService)
    {
        $this->slugger = $slugger;
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        for($j=0; $j < 5; $j++){
        $post = new Article();
        $post->setTitre($this->faker->sentence())
            ->setSlug(strtolower($this->slugger->slug($post->getTitre())))
            ->setContenu($this->faker->text(1000))
            ->setUser(mt_rand(0,1) === 1 ? $this->getReference('admin'): $this->getReference('user') )
            ->setCreateAt(new \DateTimeImmutable());
            $images = ['720c025f9657faaa7536e12ad9033737.webp','a875d0c1f8a62f1f1bf41d17bc6b85be.webp','c9a62091dc135f8deeb3c41a5ba367e2.webp'];
            for( $i=0; $i<count($images); $i++){
                $image = new Photo();
                $view = $images[$i];
                $image->setName($view);
                $post->addPhoto($image);
            }

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