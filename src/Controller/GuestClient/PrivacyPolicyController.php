<?php

namespace App\Controller\GuestClient;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PrivacyPolicyController extends AbstractController
{
    #[Route('/privacy/policy', name: 'app_privacy_policy')]
    public function index(): Response
    {
        return $this->render('GuestClient/privacy_policy/index.html.twig', [
            'controller_name' => 'PrivacyPolicyController',
        ]);
    }
}
