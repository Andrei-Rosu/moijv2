<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
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
    /**
     * @Route("/admin/user/delete/{id}", name="delete_user")
     */
    public function deleteUser(User $user, ObjectManager $manager )
    {
        $manager->remove($user);
        $manager->flush();
        return $this->redirectToRoute('admin_dashboard');
    }
}
