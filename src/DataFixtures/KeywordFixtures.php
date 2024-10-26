<?php

namespace App\DataFixtures;

use App\Entity\Keyword;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class KeywordFixtures extends Fixture
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $keywords = ['Economie', 'Politique', 'Géographie', 'Science', 'Histoire', 'Philosophie'];

        foreach ($keywords as $keywordName) {
            $keyword = new Keyword();
            $keyword->setName($keywordName);
            $slug = strtolower($this->slugger->slug($keywordName));
            $keyword->setSlug($slug);

            $manager->persist($keyword);
        }

        $manager->flush();
    }
}
