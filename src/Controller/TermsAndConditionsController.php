<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TermsAndConditionsController extends AbstractController
{
    #[Route('/terms-and-conditions', name: 'app_my_Terms_and_Conditions', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('my_account/TermsAndConditions.html.twig', [
        ]);
    }
}
