<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\HttpFoundation\File\File;
use App\Entity\Product;
use App\Enum\ProductUnit;
use App\Entity\Category;
use App\Entity\User;
use App\DataFixtures\Factory\Factory;



class ProductFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Supponiamo di avere già delle entità Category e User nel database
        $categories = $manager->getRepository(Category::class)->findAll();
        $users = $manager->getRepository(User::class)->findAll();

        for ($i = 0; $i < 10; $i++) {
            $product = new Product();

            $product->setName($faker->word)
                ->setDescription($faker->sentence(10))
                ->setPrice($faker->randomFloat(2, 1, 100)) // Prezzo tra 1 e 100
                ->setVolumeLitre($faker->optional()->randomFloat(2, 0.1, 5)) // Volume tra 0.1 e 5 litri (opzionale)
                ->setWeight($faker->optional()->randomFloat(2, 0.5, 50)) // Peso tra 0.5 e 50 kg (opzionale)
                ->setImageName($faker->image(null, 640, 480, 'product', true, true, 'product'))
                ->setImageSize($faker->numberBetween(1000, 5000)) // Simula una dimensione immagine
                ->setUpdatedAt($faker->dateTimeImmutable)
                ->setUnit($faker->randomElement(ProductUnit::cases())) // Enum unità
                ->setCategory($faker->randomElement($categories)) // Categoria casuale
                ->setUser($faker->randomElement($users)); // Utente casuale

            // Aggiungi lo stock
            $stock = new \App\Entity\Stock();
            $stock->setQuantity($faker->numberBetween(10, 100));
            $stock->setProduct($product);
            $product->setStock($stock);

            $manager->persist($product);
            $manager->persist($stock);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,  // Assicurati che UserFixtures venga eseguito prima di ProductFixture
        ];
    }
}
