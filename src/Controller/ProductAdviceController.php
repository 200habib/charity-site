<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductAdviceController extends AbstractController
{
    #[Route('/product/advice', name: 'app_product_advice')]
    public function index(): Response
    {
        return $this->render('product_advice/index.html.twig', [
            'controller_name' => 'ProductAdviceController',
        ]);
    }
}
