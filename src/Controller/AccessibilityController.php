<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AccessibilityController extends AbstractController
{
    #[Route('/accessibility', name: 'app_accessibility')]
    public function index(): Response
    {
        return $this->render('accessibility/index.html.twig', [
            'controller_name' => 'AccessibilityController',
        ]);
    }
}
