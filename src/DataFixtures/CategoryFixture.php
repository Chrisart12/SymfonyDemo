<?php

namespace App\DataFixtures;

use App\Trait\SlugFy;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CategoryFixture extends Fixture
{
    use SlugFy;

    public function load(ObjectManager $manager): void
    {

        $categories = ['patisserie', 'boulangerie', 'asiatique', 'africain', 'français', 'fast food'];

        for ($i=0; $i < count($categories); $i++) { 
            $category = new Category();

            $category->setName($categories[$i])
                    ->setSlug($this->makeSlgug($categories[$i]));
            $manager->persist($category);

            // other fixtures can get this object using the UserFixtures::ADMIN_USER_REFERENCE constant
            $this->addReference('CATEGORY' . $i, $category);
        }
        
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();

        
    }
}
