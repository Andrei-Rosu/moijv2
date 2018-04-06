<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    /**
     * @Route("/",name="root")
     *
     */
    public function root()
    {
        return $this->redirectToRoute('home');
    }
    /**
     * @Route("/home", name="home")
     * @Route("/home/page{page}", name="home_paginated")
     */
    public function index(\App\Repository\ProductRepository $productRepo, $page=1)

    {
        $products = $productRepo->findPaginated($page);
        return $this->render("home.html.twig", [
            'products'=>$products
        ]);
    }
}
