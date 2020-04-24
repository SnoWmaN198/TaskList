<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
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
    public function register(Request $request, EncoderFactoryInterface $encoderFactory, TokenStorageInterface $tokenStorage) 
    {
        $user = new User();
        $form = $this->createForm(UserFormType::class, $user, ['standalone' => true]);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $encoder = $encoderFactory->getEncoder(User::class);
            
            $user->setSalt(md5($user->getUsername()));
            $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
            $role = $user->getRoleId();
            
            $user->setPassword($password);
            $user->setRoleId($role);
            
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();
            
            $tokenStorage->setToken(
                new UsernamePasswordToken($user, null, 'main', $user->getRoleId())    
            );
            
            return $this->redirectToRoute('homepage');
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
}
