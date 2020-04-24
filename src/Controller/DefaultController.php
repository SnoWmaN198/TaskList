<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
     * Application Homepage
     * 
     * @Route("/", name="homepage")
     */
    public function index()
    {
        return $this->render('homepage.html.twig');
    }
}