<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('en_EN');

        // Create 10 fake email for random contacts
        $emails = [];
        for ($i = 1; $i < 11; $i++) {
            $emails[] = 'random-user'.$i.'@gmail.com';
        }

        // Create random contacts with random author not in the database
        for ($i = 0; $i < 15; $i++) {
            $contact = new Contact();
            $contact->setFullname($faker->name);
            $contact->setEmail($emails[rand(0, 9)]);
            $contact->setQuestion($faker->sentence.'?');
            $contact->setIsEdited($faker->boolean);
            $contact->setIsArchived($faker->boolean);
            
            $bool = $faker->boolean;
            if ($bool) {
                $contact->setReply($faker->sentence);
                $contact->setUpdatedAt(new \DateTimeImmutable());
            }
            
            $manager->persist($contact);
        }
        
        // Create 10 users in the database with password 'password' | user|1|-|2|-[...]@gmail.com
        $users = [];
        for ($i = 1; $i < 11; $i++) {
            $user = new User();
            $user->setEmail('user'.$i.'@gmail.com');
            $password = $this->hasher->hashPassword($user, 'password');
            $user->setPassword($password);

            $manager->persist($user);
            $users[] = $user;
        }

        // Create random contacts with random author
        for ($i = 0; $i < 25; $i++) {
            $user = $users[rand(0, 9)];
            
            $contact = new Contact();
            $contact->setFullname($faker->name);
            $contact->setEmail($user->getEmail());
            $contact->setQuestion($faker->sentence.'?');
            $contact->setAuthor($user);
            $contact->setIsEdited($faker->boolean);
            $contact->setIsArchived($faker->boolean);
            
            $bool = $faker->boolean;
            if ($bool) {
                $contact->setReply($faker->sentence);
                $contact->setUpdatedAt(new \DateTimeImmutable());
            }

            $manager->persist($contact);
        }

        // Create 1 user Admin 
        $admin = new User();
        $admin->setEmail('admin@gmail.com');
        $password = $this->hasher->hashPassword($admin, 'password');
        $admin->setPassword($password);
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);
        
        $manager->flush();
    }
}
