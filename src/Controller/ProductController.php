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
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
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
            $this->addFlash('success', 'Le produit a été créé avec succès.');
            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        } else {
            if ($form->isSubmitted() && !$form->isValid()) {
                $this->addFlash('error', 'Veuillez corriger les erreurs dans le formulaire.');
            }
        }
        

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }
    
#[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
public function show(Product $product, ProductRepository $productRepository, AuthorizationCheckerInterface $authorizationChecker): Response
{
    $user = $this->getUser();
    $userId = $user ? $user->getId() : null;

    // Ottieni tutti i prodotti
    $products = $productRepository->findAll();
    $forms = [];

    // Loop per generare i form per ciascun prodotto
    /** @var Product $loopProduct */
    foreach ($products as $loopProduct) {
        $quantity = $authorizationChecker->isGranted('ROLE_CHARITY_ASSOCIATION')
            ? $loopProduct->getStock()->getPurchasedQuantity()
            : $loopProduct->getStock()->getAvailableQuantity();

        if ($quantity > 0) {
            $forms[$loopProduct->getId()] = $this->createForm(ProductQuantityType::class, null, [
                'action' => $this->generateUrl('add_to_cart', ['id' => $loopProduct->getId()]),
                'method' => 'POST',
                'quantity' => $quantity
            ])->createView();
        }
    }

    return $this->render('product/show.html.twig', [
        'productForms' => $forms,
        'product' => $product, 
        'products' => $products, 
        'userId' => $userId,
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

















    
        #[Route('/', name: 'app_product_index', methods: ['GET', 'POST'])]
        public function index(
            ProductRepository $productRepository,
            SessionInterface $session,
            Request $request,
            AuthorizationCheckerInterface $authorizationChecker,
            CategoryRepository $categoryRepository,
            PaginatorInterface $paginator,
        ): Response {

            $form = $this->createForm(FilterType::class);
            $form->handleRequest($request);
            $sortPrice = '';

            $categories = $this->categoryRepository->findAll();
            $filters = $this->initializeFilters($request);
            $Type= 'id';
       
            if ($sortPrice !=='') {
                $products = $this->productRepository->findByFilters([],[$Type=>$sortPrice]);
            } else {
                $sortPrice = 'desc'; 
                $Type = 'id'; 
                $products = $this->productRepository->findByFilters([], [$Type => $sortPrice]);
            }
            
            if ($form->isSubmitted()) {
                $sortPrice = $form->get('sortPrice')->getData();
                if ($sortPrice=="") {
                    $sortPrice = 'asc'; 
                    $Type = 'id';
                } else {
                    $sortPrice = $sortPrice; 
                    $Type = 'price';
                }
            }

            $data = $productRepository->findByFilters([], [$Type => $sortPrice]);

            $paginatedProducts = $paginator->paginate(
                $data,
                $request->query->getInt('page', 1),
                1
            );
            


    
            $productForms = $this->generateProductForms($products, $authorizationChecker);
            $cart = $session->get('cart', []);
            $selectedCategory = "";
    
            return $this->render('product/index.html.twig', [
                'categories' => $categories,
                // 'products' => $products,
                'productForms' => $productForms,
                'cart' => $cart,
                'filterForm' => $filters['formView'],
                'selectedCategory' => $selectedCategory,
                'products' => $paginatedProducts 
                // 'houses' => $houses
            ]);
        }
    
























        #[Route('/change-category/{categoryId}', name: 'change_category', methods: ['GET', 'POST'])]
        public function changeCategory(
            int $categoryId,
            ProductRepository $productRepository,
            SessionInterface $session,
            Request $request,
            AuthorizationCheckerInterface $authorizationChecker
        ): Response {
            $selectedCategory = $this->categoryRepository->find($categoryId);
            $products = $productRepository->findByCategoryId($categoryId);
            $categories = $this->categoryRepository->findAll();
    

            $form = $this->createForm(FilterType::class);

            $form->handleRequest($request);



            // dd($form->isSubmitted(), $form->isValid(), $form->getData());

            $sortPrice = '';






            // $page = $request->query->getInt('page', 1);
            // $limit = $request->query->getInt('limit', 1);

            // $houses = $productRepository->paginate($page, $limit); 
 

            // $selectedCategory = $this->categoryRepository->find($categoryId);


            $categories = $this->categoryRepository->findAll();
            $filters = $this->initializeFilters($request);
            $Type= 'id';
       
            if ($sortPrice !=='') {
                $products = $this->productRepository->findByFilters([],[$Type=>$sortPrice]);
            } else {
                $sortPrice = 'desc'; 
                $Type = 'id'; 
                $products = $this->productRepository->findByFilters([], [$Type => $sortPrice]);
            }
            
            if ($form->isSubmitted()) {
                $sortPrice = $form->get('sortPrice')->getData();
                // dd($sortPrice);
                if ($sortPrice=="") {
                    $sortPrice = 'asc'; 
                    $Type = 'id';
                } else {
                    $sortPrice = $sortPrice; 
                    $Type = 'price';
                }
            }

            $criteria = [];
            if ($selectedCategory) {
                $criteria['category'] = $selectedCategory; 
            }

















            $filters = $this->initializeFilters($request);

            
            $productForms = $this->generateProductForms($products, $authorizationChecker);
            $cart = $session->get('cart', []);
    




            return $this->render('product/index.html.twig', [
                'categories' => $categories,
                'products' => $productRepository->findByFilters($criteria, [$Type=>$sortPrice]),
                'productForms' => $productForms,
                'cart' => $cart,
                'filterForm' => $filters['formView'],
                'selectedCategory' => $selectedCategory
            ]);
        }
    
        private function initializeFilters(Request $request): array
        {
            $filterForm = $this->createForm(FilterType::class);
            $filterForm->handleRequest($request);
            $filters = [
                'sortPrice' => 'asc',
                'showSellerProducts' => false,
            ];
    
            if ($filterForm->isSubmitted() && $filterForm->isValid()) {
                $filters = $filterForm->getData();
            }
    
            return [
                'sortPrice' => $filters['sortPrice'],
                'showSellerProducts' => $filters['showSellerProducts'],
                'formView' => $filterForm->createView()
            ];
        }
    
        private function generateProductForms(array $products, AuthorizationCheckerInterface $authorizationChecker): array
        {
            $productForms = [];
            foreach ($products as $product) {
                $quantity = $authorizationChecker->isGranted('ROLE_CHARITY_ASSOCIATION')
                    ? $product->getStock()->getPurchasedQuantity()
                    : $product->getStock()->getAvailableQuantity();
    
                if ($quantity > 0) {
                    $productForms[$product->getId()] = $this->createForm(ProductQuantityType::class, null, [
                        'action' => $this->generateUrl('add_to_cart', ['id' => $product->getId()]),
                        'method' => 'POST',
                        'quantity' => $quantity
                    ])->createView();
                }
            }
            return $productForms;
        }
    }
    
    
    

