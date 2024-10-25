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

        foreach($categories as $categorie){
            $category = new Category();
            $category->setName($categorie['name'])
                     ->setSlug(strtolower($this->slugger->slug($categorie['name'])));
                     
            if($categorie['parent']){
                $parent = $this->getReference($categorie['parent']);
                $category->setParent($parent);
            }
            $this->addReference($categorie['name'],$category);
            $manager->persist($category);
        }

        $manager->flush();
    }
}
