<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Livre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Créer un utilisateur de test
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'password123'));
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);

        // Créer quelques livres de test
        for ($i = 1; $i <= 10; $i++) {
            $livre = new Livre();
            $livre->setTitre('Livre ' . $i);
            $livre->setAuteur('Auteur ' . $i);
            $livre->setResume('Résumé du livre ' . $i);
            $livre->setDisponible(true);
            $livre->setEtat('neuf');
            $manager->persist($livre);
        }

        $manager->flush();
    }
}
