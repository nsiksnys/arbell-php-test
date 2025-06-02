<?php

namespace App\Controller;

use App\Entity\RoleEnum;
use App\Entity\User;
use App\Form\UserForm;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user')]
final class UserController extends AbstractController
{
    /**
     * The logger
     */
    private $logger;
    
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    
    #[Route(name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        // Only authenticated users should access this
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED', null, "A user tried to access route app_user_index without authorization.");
        if (!$this->isGranted('IS_AUTHENTICATED'))
        {
            $this->logger->error("A user tried to access route app_user_index without authorization.");
            $this->addFlash('danger', "You need to be logged in to access this page");
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        // Only authenticated users should access this
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED', null, "A user tried to access route app_user_new without authorization.");
        if (!$this->isGranted('IS_AUTHENTICATED'))
        {
            $this->logger->error("A user tried to access route app_user_new without authorization.");
            $this->addFlash('danger', "You need to be logged in to access this page");
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        
        $user = new User();
        $form = $this->createForm(UserForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get the plain text password and hash it
            // $plaintextPassword = $user->getPassword();
            // $hashedPassword = $passwordHasher->hashPassword($user, $plaintextPassword);
            // $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', "User ". $user->getUserIdentifier() ." created.");
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        // Only authenticated users should access this
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED', null, "A user tried to access route app_user_show without authorization.");
        if (!$this->isGranted('IS_AUTHENTICATED'))
        {
            $this->logger->error("A user tried to access route app_user_show without authorization.");
            $this->addFlash('danger', "You need to be logged in to access this page");
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        // Only authenticated users should access this
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED', null, "A user tried to access route app_user_edit without authorization.");
        if (!$this->isGranted('IS_AUTHENTICATED'))
        {
            $this->logger->error("A user tried to access route app_user_edit without authorization.");
            $this->addFlash('danger', "You need to be logged in to access this page");
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        $form = $this->createForm(UserForm::class, $user);
        $form->remove('password'); // Password is not changed in this view.
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', "Changes in user ". $user->getUserIdentifier() ." saved.");
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/password-change', name: 'app_user_password', methods: ['GET', 'POST'])]
    public function password(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        // Only authenticated users should access this
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED', null, "A user tried to access route app_user_password without authorization.");
        if (!$this->isGranted('IS_AUTHENTICATED'))
        {
            $this->logger->error("A user tried to access route app_user_password without authorization.");
            $this->addFlash('danger', "You need to be logged in to access this page");
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        $form = $this->createForm(UserForm::class, $user);
        $form->remove('email');
        $form->remove('name');
        $form->remove('profile');
        $form->remove('phone');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', "User ". $user->getUserIdentifier() ."'s password changed.");
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/password.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        // Only authenticated users should access this
        // $this->denyAccessUnlessGranted(RoleEnum::ADMIN->value, null, "A user tried to access route app_user_delete without authorization.");
        if (!$this->isGranted(RoleEnum::ADMIN->value))
        {
            $this->logger->error("User ". $this->getUser()->getUserIdentifier() ." tried to access route app_user_delete without authorization.");
            $this->addFlash('danger', "You are not allowed to perform this operation");
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', "User ". $user->getUserIdentifier() ." deleted.");
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
