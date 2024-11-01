<?php

namespace App\Controller;

use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ConfirmationController extends AbstractController
{
    #[Route('/order/confirmation/{id}', name: 'order_confirmation')]
    public function confirmation(Order $order): Response
    {
        return $this->render('confirmation/index.html.twig', [
            'order' => $order,
        ]);
    }
}
