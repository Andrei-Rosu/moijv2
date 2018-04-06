<?php


namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product")
 *
 */
class ProductController extends Controller
{

    /**
     * @Route("/", name="user_productlist")
     * @Route("/{page}", name="product_paginated", requirements={"page"="\d+"})
     */
    public function index(\App\Repository\ProductRepository $productRepo, $page = 1)

    {
        $productList = $productRepo->findPaginatedByUser($this->getUser(), $page);
        return $this->render("product/index.html.twig",[
            'products' => $productList
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete_product")
     */
    public function deleteProduct(Product $product, ObjectManager $manager )
    {
        if($product->getOwner()->getId() !== $this->getUser() ->getId()){
            throw $this->createAccessDeniedException('You are not allowed to delete this product');
        }
        $manager->remove($product);
        $manager->flush();
        return $this->redirectToRoute('user_productlist');
    }
    /**
     * @Route("/add", name="add_product")
     * @Route("/edit/{id}", name="edit_product")
     */
    public function editProduct(\Symfony\Component\HttpFoundation\Request $request, ObjectManager $manager, Product $product = null)
    {
        if ($product === null) {
            $product = new \App\Entity\Product();
            $group = 'insertion';
        }else{
            $oldImage =$product->getImage();
            $product->setImage(new File($product->getImage()));
            $group = 'edition';
        }
        $formProduct = $this->createForm(ProductType::class, $product, ['validation_groups'=>[$group]])
            ->add('Submit', SubmitType::class);


        $formProduct->handleRequest($request);// declenche la gestion de formulaire
        if ($formProduct->isSubmitted() && $formProduct->isValid()) {
            //enregistrement de notre produit
            $product->setOwner($this->getUser());
            $image=$product->getImage();
            if($image === null){
                $product->setImage($oldImage);
            }else{
            $newFileName=md5(uniqid()).'.'.$image->guessExtension();
            $image->move('uploads',$newFileName);
            $product->setImage('uploads/' . $newFileName);
            }
            $manager->persist($product);
            $manager->flush();
            return $this->redirectToRoute('user_productlist');


        }
        return $this->render('product/edit_product.html.twig', ['form'=>$formProduct->createView()]);

    }}