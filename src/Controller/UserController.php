<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\User2Type;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin/user')]
final class UserController extends AbstractController
{
    #[Route(name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {

        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/search', name: 'app_user_search', methods: ['GET'])]
    public function search(Request $request, UserRepository $userRepository): Response
    {
        $email = $request->query->get('email');

        $user = $userRepository->findOneBy(['email' => $email]);

        return $this->render('user/search_results.html.twig', [
            'user' => $user,
        ]);
    }


    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        
        $user = new User();

        $form = $this->createForm(User2Type::class, $user, [
            'csrf_protection' => false,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $plainPassword = $user->getPassword();
            $user->setVerified(true);
            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);

            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
            'edit_mode' => false,
        ]);
    }



    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        // dd($user);
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        
        $originalPassword = $user->getPassword();

        $form = $this->createForm(User2Type::class, $user, [
            'csrf_protection' => false,
        ]);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                // Recupera la nuova password dal form
                $password = $form->get('password')->getData();
    
                if ($password !== null && $password !== '') {
                    // Hasha e aggiorna la password solo se è stata modificata
                    $hashedPassword = $passwordHasher->hashPassword($user, $password);
                    $user->setPassword($hashedPassword);
                } else {
                    // Se il campo password è vuoto, ripristina la password originale
                    $user->setPassword($originalPassword);
                }
    
                $entityManager->flush();
    
                return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
            } else {
                // Mostra gli errori del form per il debug
                dd($form->getErrors(true, false));
            }
        }
    
        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'edit_mode' => false,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete($id, Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        
        foreach ($user->getProducts() as $product) {
            $entityManager->remove($product);
        }

        
        foreach ($user->getOrders() as $order) {
            foreach ($order->getOrderItems() as $orderItem) {
                $entityManager->remove($orderItem);
            }
            $entityManager->remove($order);
        }

        
        if ($user->getUserProfile() !== null) {
            $entityManager->remove($user->getUserProfile());
        }

        
        if ($user->getCompany() !== null) {
            $entityManager->remove($user->getCompany());
        }

        
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
