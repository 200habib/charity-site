<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ProductQuantityType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;


class HomeController extends AbstractController
{



    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $productRepository, SessionInterface $session, EntityManagerInterface $entityManager): Response
    {


        $user = $this->getUser();

        if ($user) {
            if (!$user->isVerified()) {
                $this->addFlash('error', 'Vous devez vérifier votre compte avant de pouvoir accéder.');

                return $this->redirectToRoute('app_logout');
            }
        }













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
