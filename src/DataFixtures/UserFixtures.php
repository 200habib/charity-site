<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Creazione dell'utente normale
        $user = new User();
        $user->setEmail('user@example.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'password123'));
        $user->setVerified(true); // Metodo per verificare l'utente
        $manager->persist($user);
        
        // Creazione dell'utente admin
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'adminpassword'));
        $admin->setVerified(true); // Metodo per verificare l'utente
        $manager->persist($admin);

        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'adminpassword'));
        $admin->setVerified(true); // Metodo per verificare l'utente
        $manager->persist($admin);

        // Aggiungi il riferimento per il futuro
        $this->addReference('user_admin', $admin);

        $manager->flush();
    }
}
