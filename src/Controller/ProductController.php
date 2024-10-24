<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\OrderItem;
use App\Form\ProductQuantityType;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

use App\Entity\Category;
use App\Repository\CategoryRepository;



#[Route('/product')]
class ProductController extends AbstractController
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }


    #[Route('/', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository, SessionInterface $session): Response
    {
        $categories = $this->categoryRepository->findAll();
        $products = $productRepository->findAll();

        // dd($categories);
        $forms = [];
    
        foreach ($products as $product) {
            $forms[$product->getId()] = $this->createForm(ProductQuantityType::class, null, [
                'action' => $this->generateUrl('add_to_cart', ['id' => $product->getId()]),
                'method' => 'POST'
            ])->createView();
        }

        $cart = $session->get('cart', []);
    
        return $this->render('product/index.html.twig', [
            'categories' => $categories,
            'products' => $products,
            'forms' => $forms,
            'cart' => $cart
        ]);
    }

    #[IsGranted('ROLE_SELLER')]
    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_SELLER')) {
            throw new AccessDeniedException('Vous n\'avez pas l\'autorisation de créer un produit.');
        }

        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }
    
    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product, ProductRepository $productRepository): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
            'products' => $productRepository->findAll(),
        ]);
    }

    #[IsGranted('ROLE_SELLER')]
    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $orderItems = $product->getOrderItems();
        $user = $this->getUser();
        $price = $orderItems->getPrice();
        dd($price);

        if ($product->getSeller() !== $user) {
            throw new AccessDeniedException('Vous n\'avez pas l\'autorisation de modifier ce produit.');
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_SELLER')]
    #[Route('/{id}/delete', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        
        $user = $this->getUser();


        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }





    #[Route('/change-category/{categoryId}', name: 'change_category', methods: ['GET'])]
    public function changeCategory(int $categoryId, ProductRepository $productRepository, SessionInterface $session): Response
    {
        $products = $productRepository->findByCategoryId($categoryId);
        $categories = $this->categoryRepository->findAll();
        
        $forms = [];
    
        foreach ($products as $product) {
            $forms[$product->getId()] = $this->createForm(ProductQuantityType::class, null, [
                'action' => $this->generateUrl('add_to_cart', ['id' => $product->getId()]),
                'method' => 'POST'
            ])->createView();
        }

        $cart = $session->get('cart', []);
    
        return $this->render('product/index.html.twig', [
            'categories' => $categories,
            'products' => $products,
            'forms' => $forms,
            'cart' => $cart
        ]);
    }
}
