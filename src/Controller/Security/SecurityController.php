<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class for login & register page
 */
class SecurityController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {}

    /**
     * @Route("/login", name="app_login", methods={"GET", "POST"})
     */
    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // Check if user is already logged in
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('Security/login.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername,
        ]);
    }

    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/register", name="app_register", methods={"GET", "POST"})
     */
    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordEncoder,
    ): Response
    {
        // Check if user is already logged in
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $user = new User();

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash(
                'success', 'Your account has been created successfully'
            );

            return $this->redirectToRoute('app_login');
        }

        return $this->render('Security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
