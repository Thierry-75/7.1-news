<?php
namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryFixtures extends Fixture
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $categories = [
            ['name' => 'France', 'parent' => null],
            ['name' => 'Politique', 'parent' => 'France'],
            ['name' => 'Monde', 'parent' => null],
            ['name' => 'Informatique', 'parent' => 'Monde'],
            ['name' => 'Économie', 'parent' => 'Monde'],
            ['name' => 'Association', 'parent' => 'France'],
        ];

        foreach ($categories as $categoryData) {
            $category = new Category();
            $category->setName($categoryData['name']);
            $slug = strtolower($this->slugger->slug($categoryData['name']));
            $category->setSlug($slug);

            if ($categoryData['parent']) {
                $parent = $this->getReference($categoryData['parent']);
                $category->setParent($parent);
            }

            $this->addReference($categoryData['name'], $category);
            $manager->persist($category);
        }

        $manager->flush();
    }
}