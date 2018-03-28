<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    /**
     * @Route("/admin/dashboard", name="admin_dashboard")
     */
    public function index(\App\Repository\UserRepository $userRepo)

    {
        $userList = $userRepo->findAll();
            return $this->render("admin/dashboard.html.twig",[
            'users' => $userList
        ]);
    }
}
