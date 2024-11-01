<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Order;
use App\Entity\OrderItem;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ProductQuantityType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;




class CartController extends AbstractController
{
    #[Route('/add-to-cart/{id}', name: 'add_to_cart')]
    public function index(
        Product $product,
        Request $request,
        SessionInterface $session
    ): Response {
    
        $form = $this->createForm(ProductQuantityType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $quantity = $form->get('quantity')->getData();
    
            $cart = $session->get('cart', []);
    
            if (empty($cart[$product->getId()])) {
                $cart[$product->getId()] = $quantity;
            } else {
                $cart[$product->getId()] += $quantity;
            }
    
            $session->set('cart', $cart);
        }
    
        return $this->redirectToRoute('app_product_index');
    }


    #[Route('/cart', name: 'cart')]
    public function show(SessionInterface $session, ProductRepository $productRepository) : Response
    {
        
        $cart = $session->get('cart', []);
        $products= [];
        $total = 0; 

        foreach ($cart as $id => $quantity) {
            $product = $productRepository->find($id);
            if ($product !== null) {
            $products[] = $product;
            $total += $product->getPrice() * $quantity;
            }
        }


        return $this->render( 'cart/index.html.twig', [
            'products' => $products,
            'totalPrice' => $total
            // 'cart' => $cart
        ]);
    }


    #[Route('/cart/checkout', name: 'cart_checkout')]
    public function checkout(Request $request, ProductRepository $productRepository, EntityManagerInterface $entityManager): Response
    {
        $cart = $request->getSession()->get('cart', []);
        $totalPrice = 0;
        $user = $this->getUser();
    
        $order = new Order();
        $order->setUser($user);
        $order->setCreatedAt(new \DateTimeImmutable());
        $order->setStatus('Pending');
    
        foreach ($cart as $productId => $quantity) {
            $product = $productRepository->find($productId);
            if ($product) {
                $stock = $product->getStock();
    
                if ($stock && $stock->getAvailableQuantity() >= $quantity) {
                    if (!in_array("ROLE_CHARITY_ASSOCIATION", $user->getRoles())) {
                        $stock->setAvailableQuantity($stock->getAvailableQuantity() - $quantity);
                        $stock->setPurchasedQuantity(($stock->getPurchasedQuantity() ?? 0) + $quantity);
                    } else {
                        $stock->setPurchasedQuantity(($stock->getPurchasedQuantity() ?? 0) - $quantity);
                    }
    
                    if ($stock->getAvailableQuantity() <= 0) {
                        $entityManager->remove($product);
                    }
    
                    $orderItem = new OrderItem();
                    $orderItem->setProduct($product);
                    $orderItem->setQuantity($quantity);
                    $orderItem->setPrice($product->getPrice());
                    $order->addOrderItem($orderItem);
    
                    $totalPrice += $product->getPrice() * $quantity;
                    $entityManager->persist($orderItem);
                    $entityManager->persist($stock);
                } else {
                    $this->addFlash('error', "La quantité demandée pour le produit {$product->getName()} n'est pas disponible.");
                    return $this->redirectToRoute('cart');
                }
            }
        }
    
        $order->setTotalPrice($totalPrice);
        $entityManager->persist($order);
        $entityManager->flush();
    
        $request->getSession()->remove('cart');
    
        return $this->redirectToRoute('order_confirmation', ['id' => $order->getId()]);
    }
    
    
}