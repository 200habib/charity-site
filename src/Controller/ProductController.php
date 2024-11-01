<?php

namespace App\Controller;


use App\Entity\Stock;
// use App\Form\StockType;


use App\Entity\Product;
use App\Form\FilterType;
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
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

#[Route('/product')]
class ProductController extends AbstractController
{
    private CategoryRepository $categoryRepository;
    private ProductRepository $productRepository;

    public function __construct(CategoryRepository $categoryRepository, ProductRepository $productRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
    }



    #[Route('/', name: 'app_product_index', methods: ['GET', 'POST'])]
    public function index(
        ProductRepository $productRepository,
        SessionInterface $session,
        Request $request,
        AuthorizationCheckerInterface $authorizationChecker
    ): Response {
        $categories = $this->categoryRepository->findAll();

        // Retrieve products and create forms for each
        $products = $this->productRepository->findAll();
        $forms = [];
        /** @var Product $product */
        foreach ($products as $product) {
            $quantity = $authorizationChecker->isGranted('ROLE_CHARITY_ASSOCIATION') ? $product->getStock()->getPurchasedQuantity() : $product->getStock()->getAvailableQuantity();

            if($quantity > 0) {
                $forms[$product->getId()] = $this->createForm(ProductQuantityType::class, null, [
                    'action' => $this->generateUrl('add_to_cart', ['id' => $product->getId()]),
                    'method' => 'POST',
                    'quantity' => $quantity
                ])->createView();
            }
        }
    

        $cart = $session->get('cart', []);

        $filterForm = $this->createForm(FilterType::class);
        $filterForm->handleRequest($request);
    
        $filters = [
            'sortPrice' => 'asc',
            'showSellerProducts' => false,
        ];
    
        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $filters = $filterForm->getData();
        }

        
        $products = $this->productRepository->findByFilters(
            $filters['sortPrice'],
            $filters['showSellerProducts'],
        );
        dump($products);

        
    $selectedCategor= "";
        return $this->render('product/index.html.twig', [
            'categories' => $categories,
            'products' => $products,
            'productForms' => $forms,
            'cart' => $cart,
            'filterForm' => $filterForm->createView(),
            'selectedCategory' => $selectedCategor
        ]);
    }

    #[IsGranted('ROLE_SELLER')]
    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_SELLER')) {
            throw new AccessDeniedException('Vous n\'avez pas l\'autorisation de créer un produit.');
        }
        $user = $this->getUser();
        $product = new Product();
        $stock = new Stock();
        $product->setStock($stock);
        $product->setUser($user);
    
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


        if ($product->getUser() !== $user) {
            $entityManager->remove($user);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_homepage', [
                'message' => 'Votre profil a été supprimé car vous n\'êtes pas autorisé à modifier ce produit.'
                ]);
        }




         $prices = [];
        
         
         foreach ($orderItems as $orderItem) {
        
            $prices[] = $orderItem->getPrice(); 
        
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
        $selectedCategory = $this->categoryRepository->find($categoryId);
        $productForms = []; 
        $filterForm = $this->createForm(FilterType::class)->createView();

        foreach ($products as $product) {
            $productForms[$product->getId()] = $this->createForm(ProductQuantityType::class, null, [
                'action' => $this->generateUrl('add_to_cart', ['id' => $product->getId()]),
                'method' => 'POST'
            ])->createView();
        }
    
        $cart = $session->get('cart', []);
    
        return $this->render('product/index.html.twig', [
            'categories' => $categories,
            'products' => $products,
            'productForms' => $productForms, 
            'cart' => $cart,
            'filterForm' => $filterForm,
            'selectedCategory' => $selectedCategory 
        ]);
        
        
    }
    
    
}
