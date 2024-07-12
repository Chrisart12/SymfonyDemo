<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(private readonly UserPasswordHasherInterface $hasher) 
    {

    }
    public function load(ObjectManager $manager): void
    {

        $user = new User();

        $user->setRoles(['ROLE_ADMIN'])
                ->setEmail('issa@gmail.com')
                ->setFirstname('issa')
                ->setLastname('issifou')
                ->setPassword($this->hasher->hashPassword($user, 'bonheur'))
                ;
        $manager->persist($user);
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();

      
    }
}
