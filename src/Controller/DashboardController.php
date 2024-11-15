<?php

namespace App\Controller;


use App\Entity\User;
use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;



#[IsGranted('ROLE_ADMIN')]
class DashboardController extends AbstractController
{

    #[Route('/admin/dashboard', name: 'app_dashboard')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $userCount = $entityManager->getRepository(User::class)->count([]);

        $categoryCount = $entityManager->getRepository(Category::class)->count([]);

        $productCount = $entityManager->getRepository(Product::class)->count([]);

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'user_count' => $userCount,
            'category_count' => $categoryCount,
            'product_count' => $productCount,
        ]);
    }
}
