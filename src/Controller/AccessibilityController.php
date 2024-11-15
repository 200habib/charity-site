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


    #[Route('/Accessibility/text', name: 'app_my_account_Accessibility_text', methods: ['GET', 'POST'])]
    public function AccessibilityText(): Response
    {
        return $this->render('my_account/Accessibility.html.twig', [
            'controller_name' => 'MyAccountController',
        ]);
    }

    #[Route('/Accessibility/cursor', name: 'app_my_account_Accessibility_cursor', methods: ['GET', 'POST'])]
    public function AccessibilityCursor(): Response
    {
        return $this->render('my_account/AccessibilityCursor.html.twig', [
            'controller_name' => 'MyAccountController',
        ]);
    }

}
