<?php


namespace App\Controller;

use App\Entity\Product;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{

    /**
     * @Route("/user/productlist", name="user_productlist")
     */
    public function index(\App\Repository\ProductRepository $productRepo)

    {
        $productList = $productRepo->findAll();
        return $this->render("user/productlist.html.twig",[
            'products' => $productList
        ]);
    }

    /**
     * @Route("/user/user/delete/{id}", name="delete_product")
     */
    public function deleteProduct(Product $product, ObjectManager $manager )
    {
        $manager->remove($product);
        $manager->flush();
        return $this->redirectToRoute('user_productlist');
    }
    /**
     * @Route("/user/product/add", name="add_product")
     * @Route("/user/product/edit/(id)", name="edit_product")
     */
    public function editUser(\Symfony\Component\HttpFoundation\Request $request, ObjectManager $manager, Product $product = null)
    {
        if ($product === null) {
            $product = new User();
        }
        $formProduct = $this->createForm(ProductType::class, $product)
            ->add('Submit', SubmitType::class);


        $formProduct->handleRequest($request);// declenche la gestion de formulaire
        if ($formProduct->isSubmitted() && $formProduct->isValid()) {
            //enregistrement de notre utilisateur
            $product->setRegisterDate(new  \DateTime('now'));
            $manager->persist($product);
            $manager->flush();
            return $this->redirectToRoute('user_productlist');


        }

    }}