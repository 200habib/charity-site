<?php

namespace App\Controller;

use App\Form\AdviceAssociationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdviceAssociationController extends AbstractController
{
    #[IsGranted('ROLE_CHARITY_ASSOCIATION')]
    #[Route('/advice/association', name: 'app_advice_association')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(AdviceAssociationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = $this->getUser();

            $email = (new Email())
                ->from('hello@example.com')
                ->to('admin@example.com')
                ->subject('New Message from User')
                ->html(sprintf(
                    '<p>Nouveau message de l\'association (Email de l\'utilisateur : %s)</p>
                    <p><strong>Conseil :</strong> %s</p>
                    <p>Ce message a été envoyé pour aider les clients de notre page à choisir lorsqu\'ils ne savent pas quoi acheter. Merci de prendre en compte ces recommandations.</p>',
                    $user->getEmail(),
                    nl2br(htmlspecialchars($data['message']))
                ));
                
            $mailer->send($email);
            $this->addFlash('success', 'Your message has been sent!');
            return $this->redirectToRoute('app_advice_association');
        }

        return $this->render('advice_association/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
