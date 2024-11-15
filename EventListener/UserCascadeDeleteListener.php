<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManagerInterface;

class UserCascadeDeleteListener
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function preRemove(User $user, LifecycleEventArgs $args): void
    {
        // Rimuovi gli ordini associati all'utente
        foreach ($user->getOrders() as $order) {
            $this->entityManager->remove($order);
        }

        // Rimuovi i prodotti associati all'utente
        foreach ($user->getProducts() as $product) {
            $this->entityManager->remove($product);
        }

        // Esegui effettivamente le eliminazioni
        $this->entityManager->flush();
    }
}
