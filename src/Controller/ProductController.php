<?php


namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends Controller
{

    /**
     * @Route("/user/productlist", name="user_productlist")
     * @Route("/user/{page}", name="product_paginated")
     */
    public function index(\App\Repository\ProductRepository $productRepo, $page = 1)

    {
        $productList = $productRepo->findPaginated($page);
        return $this->render("user/productlist.html.twig",[
            'products' => $productList
        ]);
    }

    /**
     * @Route("/user/delete/{id}", name="delete_product")
     */
    public function deleteProduct(Product $product, ObjectManager $manager )
    {
        $manager->remove($product);
        $manager->flush();
        return $this->redirectToRoute('user_productlist');
    }
    /**
     * @Route("/product/add", name="add_product")
     * @Route("/product/edit/(id)", name="edit_product")
     */
    public function editProduct(\Symfony\Component\HttpFoundation\Request $request, ObjectManager $manager, Product $product = null)
    {
        if ($product === null) {
            $product = new \App\Entity\Product();
        }
        $formProduct = $this->createForm(ProductType::class, $product)
            ->add('Submit', SubmitType::class);


        $formProduct->handleRequest($request);// declenche la gestion de formulaire
        if ($formProduct->isSubmitted() && $formProduct->isValid()) {
            //enregistrement de notre produit
            $manager->persist($product);
            $manager->flush();
            return $this->redirectToRoute('user_productlist');


        }
        return $this->render('user_productlist/edit_product.html.twig', ['form'=>$formProduct->createView()]);
    }}