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
use Symfony\Component\Security\Http\Attribute\IsGranted;



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
        $user = $this->getUser();
    
        if ($user) {
            $company = $user->getCompany();
            
            $creditPoints = $company ? $company->getCreditPoints() : null;

            $session->set('creditPoints', $creditPoints);
        }




        
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


        $session->set('total', $total);
        // dd($cart);
        return $this->render( 'cart/index.html.twig', [
            'products' => $products,
            'totalPrice' => $total,
            'creditPoints' => $session->get('creditPoints', 0),
        ]);
    }



    #[IsGranted('ROLE_USER')]
    #[Route('/cart/checkout', name: 'cart_checkout')]
    public function checkout(
        Request $request,
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager,
        SessionInterface $session
    ): Response {




$cart = $request->getSession()->get('cart', []);
        if (empty($cart)) {

            
            $this->addFlash('success', [
                'title' => 'Annonce publiée !',
                'message' => 'Annonce ajoutée avec succès'
            ]);

            return $this->redirectToRoute('cart');
        }

  
        
        $totalPrice = 0;
        $user = $this->getUser();
        
        if (!$user) {
            // $this->addFlash('error', "Vous devez être connecté pour effectuer un achat.");
            return $this->redirectToRoute('app_login');
        }

        // Ottieni i dati dell'azienda
        $companyName = $user->getCompany() ? $user->getCompany()->getCompanyName() : null;
        $companyAddress = $user->getCompany() ? $user->getCompany()->getCompanyAddress() : null;
        $email = $user->getEmail();
        // $phoneNumber = $user->getPhoneNumber();

        // Creazione dell'ordine
        $order = new Order();
        $order->setUser($user);
        $order->setCreatedAt(new \DateTimeImmutable());
        $order->setStatus('Pending');
    
        // Calcolo del prezzo totale
        foreach ($cart as $productId => $quantity) {
            $product = $productRepository->find($productId);
            if ($product) {
                $totalPrice += $product->getPrice() * $quantity;
            }
        }

        // Gestione dei punti per le associazioni benefiche
        $total = $session->get('total', 0);
        if ($this->isGranted('ROLE_CHARITY_ASSOCIATION')) {
            $availablePoints = $user->getCompany()->getCreditPoints();
            if ($availablePoints < $total * 2) {
                $this->addFlash('error', "Vous n'avez pas assez de points pour compléter cet achat.");
                return $this->redirectToRoute('cart');
            } else {
                $user->getCompany()->setCreditPoints($availablePoints - $total * 2);
                $entityManager->persist($user->getCompany());
            }
        }

        // Aggiorna stock e crea orderItem
        foreach ($cart as $productId => $quantity) {
            $product = $productRepository->find($productId);
            if ($product) {
                $stock = $product->getStock();
                if ($stock && $stock->getAvailableQuantity() >= $quantity) {
                    // Modifica stock per l'acquisto
                    if (!$this->isGranted('ROLE_CHARITY_ASSOCIATION')) {
                        $stock->setAvailableQuantity($stock->getAvailableQuantity() - $quantity);
                        $stock->setPurchasedQuantity(($stock->getPurchasedQuantity() ?? 0) + $quantity);
                    } else {
                        $stock->setPurchasedQuantity(($stock->getPurchasedQuantity() ?? 0) - $quantity);
                    }

                    // Rimuove il prodotto se lo stock è esaurito
                    if ($stock->getAvailableQuantity() <= 0) {
                        $entityManager->remove($product);
                    }

                    // Crea un orderItem per l'ordine
                    $product->setBuyerId($user->getId());
                    $orderItem = new OrderItem();
                    $orderItem->setProduct($product);
                    $orderItem->setQuantity($quantity);
                    $orderItem->setPrice($product->getPrice());
                    $order->addOrderItem($orderItem);

                    // Persisti l'orderItem e stock
                    $entityManager->persist($orderItem);
                    $entityManager->persist($stock);
                } else {
                    // $this->addFlash('error', "La quantité demandée pour le produit {$product->getName()} n'est pas disponible.");
                    return $this->redirectToRoute('cart');
                }
            }
        }

        // Impostazione del prezzo totale dell'ordine
        $order->setTotalPrice($totalPrice);
        $entityManager->persist($order);
        $entityManager->flush();

        // Svuota il carrello dopo l'acquisto
        $request->getSession()->remove('cart');

        // Mostra un riepilogo dell'acquisto
        $purchasedProducts = [];
        foreach ($cart as $productId => $quantity) {
            $product = $productRepository->find($productId);
            if ($product) {
                $purchasedProducts[] = [
                    'product' => $product->getName(),
                    'quantity' => $quantity,
                    'totalPrice' => $product->getPrice() * $quantity,
                ];
            }
        }

        $this->addFlash('success', "Votre achat a été effectué avec succès.");

        $user = $this->getUser();
    
        if ($user) {
            $company = $user->getCompany();
            
            $creditPoints = $company ? $company->getCreditPoints() : null;

            $session->set('creditPoints', $creditPoints);
        }


        return $this->render('confirmation/index.html.twig', [
            'email' => $email,
            'companyName' => $companyName,
            'companyAddress' => $companyAddress,
            'order' => $order, 
            'creditPoints' => $session->get('creditPoints', 0),
        ]);
    }



}

    
    
    
    
    
    
