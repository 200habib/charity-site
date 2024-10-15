<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactUsController extends AbstractController
{
    #[Route('/contactUs', name: 'app_contact_us')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $email = (new Email())
                ->from('hello@example.com')
                ->to('you@example.com')
                ->subject('New Contact Message')
                ->html('<p>You have received a new message:</p><p><strong>Name:</strong> '.$data['name'].'</p><p><strong>Email:</strong> '.$data['email'].'</p><p><strong>Message:</strong> '.$data['message'].'</p>');
            $mailer->send($email);
            $this->addFlash('success', 'Your message has been sent!');
            return $this->redirectToRoute('app_contact_us');
        }

        return $this->render('contact_us/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
