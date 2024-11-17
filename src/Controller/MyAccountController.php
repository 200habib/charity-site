<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\UserProfile;
use App\Entity\Company;

use App\Form\UserProfileType;
use App\Form\CompanyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Form\DeleteAccountType;


#[IsGranted('ROLE_USER')]
#[Route('/account')]
class MyAccountController extends AbstractController
{

    private UserPasswordHasherInterface $passwordHasher;
    private EntityManagerInterface $entityManager;

    public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager)
    {
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
    }
    
    #[Route('/', name: 'app_my_account', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        return $this->render('my_account/index.html.twig', [
            'controller_name' => 'MyAccountController',
        ]);
    }




    #[IsGranted('ROLE_USER')]
    #[Route('/profile', name: 'app_my_account_account', methods: ['GET', 'POST'])]
    public function editProfile(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        
        if (!$user) {
            throw $this->createAccessDeniedException();
        }
        
        $userProfile = $user->getUserProfile();
        // dd($userProfile->setUser($user));
        if (!$userProfile) {
            $userProfile = new UserProfile();
            $userProfile->setUser($user);
            // dd($userProfile->setUser($user));
            $entityManager->persist($userProfile);
        }

        $form = $this->createForm(UserProfileType::class, $userProfile);
        $form->handleRequest($request);

        // dd($form->isSubmitted() && $form->isValid());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();
                $this->addFlash('success', 'Profile updated successfully!');
                return $this->redirectToRoute('app_my_account_account');
            } catch (\Exception $e) {
                $this->addFlash('error', 'An error occurred while saving your profile.');
            }
        }

        return $this->render('my_account/user.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
   

    



    
    #[IsGranted('ROLE_USER')]
    #[Route('/user/edit', name: 'app_my_account_edit', methods: ['GET', 'POST'])]
    public function UserEdit(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_my_account_edit');
        }

        return $this->render('my_account/user_edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    #[IsGranted('ROLE_USER')]
    #[Route('/user/delete', name: 'app_my_account_delete', methods: ['GET', 'POST'])]
    public function UserDelete(
        Request $request, 
        UserPasswordHasherInterface $passwordHasher, 
        EntityManagerInterface $entityManager, 
        TokenStorageInterface $tokenStorage
    ): Response {
        $user = $this->getUser();
        $deleteAccountForm = $this->createForm(DeleteAccountType::class);
        $deleteAccountForm->handleRequest($request);
    
        if ($deleteAccountForm->isSubmitted() && $deleteAccountForm->isValid()) {
            $password = $deleteAccountForm->get('password')->getData();
    
            if ($passwordHasher->isPasswordValid($user, $password)) {
    
                foreach ($user->getOrders() as $order) {
                    foreach ($order->getOrderItems() as $orderItem) {
                        $entityManager->remove($orderItem);
                    }
                    // Ora rimuovi l'ordine stesso
                    $entityManager->remove($order);
                }
    
                foreach ($user->getProducts() as $product) {
                    foreach ($product->getOrderItems() as $orderItem) {
                        $entityManager->remove($orderItem);
                    }
                    $entityManager->remove($product);
                }
    
                $entityManager->remove($user);
                $entityManager->flush();
    
                $tokenStorage->setToken(null);
                $request->getSession()->invalidate();
    
                $this->addFlash('success', 'Votre compte a été supprimé avec succès.');
    
                return $this->redirectToRoute('app_home');
            } else {
                $this->addFlash('error', 'Mot de passe incorrect.');
            }
        }
    
        return $this->render('my_account/delete.html.twig', [
            'form' => $deleteAccountForm->createView(),
        ]);
    }
    
    
    // #[Route('/Terms-and-Conditions', name: 'app_my_Terms_and_Conditions', methods: ['GET', 'POST'])]
    // public function TermsAndConditions(): Response
    // {
    //     return $this->render('my_account/TermsAndConditions.html.twig', [
    //     ]);
    // }
}
