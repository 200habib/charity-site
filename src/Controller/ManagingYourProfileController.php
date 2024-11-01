<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ManagingYourProfileController extends AbstractController
{
    #[Route('/managing/your/profile', name: 'app_managing_your_profile')]
    public function index(): Response
    {
        return $this->render('managing_your_profile/index.html.twig', [
            'controller_name' => 'ManagingYourProfileController',
        ]);
    }
}
