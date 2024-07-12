<?php

namespace App\DataFixtures;

use Faker\Factory;
use DateTimeImmutable;
use App\Entity\RecipeLike;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class RecipeLikeFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i=0; $i < 20 ; $i++) { 
            $recipeLike = new RecipeLike();
            $recipeLike->setUser($this->getReference('USER'. $faker->numberBetween(1, 10)))
                    ->setRecipe($this->getReference('RECIPE'. $faker->numberBetween(1, 18)))
                    ->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime()))
                    ->setUpdatedAt(DateTimeImmutable::createFromMutable($faker->dateTime()));

            $manager->persist($recipeLike);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }

    /**
     * C'est une fonction de DependentFixtureInterface
     * permet de exécuter les fictures dans l'ordre
     *
     * @return void
     */
    public function getDependencies()
    {
        return [
            RecipeFixtures::class,
            UserFixture::class
        ];
    }
}
