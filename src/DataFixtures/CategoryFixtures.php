<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $category = new Category();
        $category->setActive(true);
        $category->setSort(1);
        $category->setLang('en');
        $category->setTitle('General');
        $category->setSlug('general');
        $category->setBrief('In this category, we will discuss general news');
        $category->setMetaTitle('General News | Moutaz Blog');

        $manager->persist($category);
        $manager->flush();
    }
}
