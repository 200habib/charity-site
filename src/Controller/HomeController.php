<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ProductQuantityType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $productRepository, SessionInterface $session): Response
    {
        $products = $productRepository->findAll();
        $forms = [];
    
        foreach ($products as $product) {
            $forms[$product->getId()] = $this->createForm(ProductQuantityType::class, null, [
                'action' => $this->generateUrl('add_to_cart', ['id' => $product->getId()]),
                'method' => 'POST'
            ])->createView();
        }

        $cart = $session->get('cart', []);
    
        return $this->render('home/home.html.twig', [
            'products' => $products,
            'forms' => $forms,
            'cart' => $cart
        ]);
    }
}
