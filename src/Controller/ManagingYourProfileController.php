<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[IsGranted('ROLE_USER')]
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
