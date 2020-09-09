<?php


namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class CategoryFixtures extends Fixture
{
    const CATEGORIES = [
            'Boîte à outils',
            'Checklist',
    ];

    public function load(ObjectManager $manager)
    {
        $counter = 0;
        foreach (self::CATEGORIES as $data) {
            $category = new Category();
            $category->setName($data);
            if ($data === 'Checklist') {
                $category->setAccess('manager');
            }
            $manager->persist($category);
            $this->addReference('category_' . $counter, $category);
            $counter++;
        }
        $manager->flush();
    }
}
