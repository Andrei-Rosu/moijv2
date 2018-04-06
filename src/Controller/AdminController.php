<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\Common\Persistence\ObjectManager;
use http\Env\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
     * @Route("/admin/product/delete/{id}", name="delete_user")
     */
    public function deleteUser(User $user, ObjectManager $manager )
    {
        $manager->remove($user);
        $manager->flush();
        return $this->redirectToRoute('admin_dashboard');
    }
    /**
     * @Route("/admin/product/add", name="add_user")
     * @Route("/admin/product/edit/(id)", name="edit_user")
     */
    public function editUser(\Symfony\Component\HttpFoundation\Request $request, ObjectManager $manager, User $user = null)
    {
        if($user === null){
            $user = new User();
        }
        $formUser = $this->createForm(UserType::class, $user)
        ->add('Submit', SubmitType::class);



        $formUser->handleRequest($request);// declenche la gestion de formulaire
        if($formUser->isSubmitted() && $formUser->isValid()){
            //enregistrement de notre utilisateur
            $user->setRegisterDate(new  \DateTime('now'));
            $user->setRoles('ROLE_USER');
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('admin_dashboard');


        }
        return $this->render('admin/edit_user.html.twig',['form'=>$formUser->createView()]);
    }
}
