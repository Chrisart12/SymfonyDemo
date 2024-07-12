<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Trait\SlugFy;
use App\Entity\Recipe;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use FakerRestaurant\Provider\fr_FR\Restaurant;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class RecipeFixtures extends Fixture implements DependentFixtureInterface
{
    use SlugFy;
    
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new Restaurant($faker));

        $category_id = $faker->numberBetween(1, 6);

        for ($i=0; $i < 20 ; $i++) { 
            $title = $faker->foodName();
            $recipe = new Recipe();
            $recipe->setTitle($title)
                    ->setSlug($this->makeSlgug($title))
                    ->setCategory($this->getReference('CATEGORY'. $faker->numberBetween(1, 5)))
                    ->setUser($this->getReference('USER'. $faker->numberBetween(1, 10)))
                    ->setRecipeFilename('recipe_1720431557_4.jpg')
                    ->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime()))
                    ->setUpdatedAt(DateTimeImmutable::createFromMutable($faker->dateTime()))
                    ->setContent($faker->paragraphs(3, true))
                    ->setDuration($faker->numberBetween(5, 60));

            $manager->persist($recipe);

            $this->addReference('RECIPE' . $i, $recipe);
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
            CategoryFixture::class,
            UserFixture::class
        ];
    }
}
