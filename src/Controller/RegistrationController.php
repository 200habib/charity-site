<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

use App\Repository\UserRepository;

use App\Service\JWTService;
use App\Service\SendEmailService;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, 
    UserPasswordHasherInterface $userPasswordHasher, 
    Security $security, 
    EntityManagerInterface $entityManager,
    JWTService $jwt,
    SendEmailService $mail
    ): Response
    {

        if ($this->getUser()) {
            return $this->redirectToRoute('app_home'); 
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $entityManager->persist($user);
            $user->setVerified(false);
            $entityManager->flush();


            $header = [
                'typ' => 'JWT',
                'alg' => 'HS256'
            ];
    
            $payload = [
                'user_id' => $user->getId()
            ];
    
            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));


            $mail->send(
                'no-reply@openblog.test',
                $user->getEmail(),
                'Activation de votre compe sur le site Ulisse',
                'register',
                compact('user', 'token')
            );

            // dd($token);
            // return $security->login($user, 'form_login', 'main');
            return $this->redirectToRoute('app_home');
        }



        

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }







    #[Route('/verif/{token}', name: 'verify_user')]
    public function verifUser(
        UserRepository $UserRepository,
    EntityManagerInterface $em,
    JWTService $jwt,
    $token
    ): Response {
        if($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwtsecret'))) {
            $payload = $jwt->getPayload($token);

            $user = $UserRepository->find($payload['user_id']);
            if ($user && !$user->isVerified()) {
                $user->setVerified(true);
                $em->flush();

                return $this->redirectToRoute('app_home');
            }
            // dd($payload);
        }
    }
}
