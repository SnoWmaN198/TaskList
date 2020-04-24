<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Form\UserFormType;

class UserController extends AbstractController
{
    /**
     * Register
     *
     * Register function for a new user with form submission and validation 
     * 
     * @Route("/register", name="register") 
     */    
    public function register(Request $request, UserPasswordEncoderInterface $encoder) 
    {
        $manager = $this->getDoctrine()->getManager();
        $user = new User();
        $form = $this->createForm(UserFormType::class, $user, ['standalone' => true]);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            
            $manager->persist($user);
            $manager->flush();
            
            return $this->redirectToRoute('login');
        }
        
    return $this->render(
        'user/register.html.twig',
        ['formObj' => $form->createView()]
        );
    }

    /**
     * Login for an existing user
     * 
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'user/login.html.twig',
            [
                'error'        => $error,
                'lastUsername' => $lastUsername
            ]
        );
    }

    /**
     * Fake logout route for Symfony
     * 
     * @Route("/logout", name="logout")
     */
    public function logout() 
    {
        throw new \RuntimeException('This should not be called directly');
    }
}
