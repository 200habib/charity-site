<?php

namespace App\Controller\GuestClient;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SponsorController extends AbstractController
{
    #[Route('/sponsor', name: 'app_sponsor')]
    public function index(): Response
    {
        return $this->render('GuestClient/sponsor/index.html.twig', [
            'controller_name' => 'SponsorController',
        ]);
    }
}
