<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher) 
    {

    }
    
    public function load(ObjectManager $manager): void
    {
        for ($i=1; $i <= 10 ; $i++) { 

            $user = new User();

            $user->setRoles(['ROLE_USER'])
                    ->setEmail('issa'.$i.'@gmail.com')
                    ->setFirstname('issa'.$i)
                    ->setLastname('issifou'.$i)
                    ->setPassword($this->hasher->hashPassword($user, 'bonheur'));
            $manager->persist($user);

            $this->addReference('USER' . $i, $user);
        }
        
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
