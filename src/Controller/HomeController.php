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
     */
    public function index(\App\Repository\UserRepository $userRepo)

    {
        $userList = $userRepo->findAll();
        $message = 'Bonjour à tous';
        return $this->render("home.html.twig",[
            'msg' => $message
        ]);
    }
}
