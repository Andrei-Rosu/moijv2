<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Cocur\Slugify\Slugify;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Repository\ProductRepository;

/**
 * @Route("/tag")
 */

class TagController extends Controller
{
    /**
     * @Route("/{slug}/product", name="tag")
     * @Route("/{slug}/product/{page}", name="tag_paginated")
     */
    public function product(Tag $tag, ProductRepository $productRepo, $page=1)
    {


        $tagProductList = $productRepo->findPaginatedByTag($tag, $page);


        return $this->render('tag/product.html.twig', [
            'tag' => $tag,
            'products'=>$tagProductList,
        ]);
    }

    /**
     * @Route("", name="search_tag")
     */
    public function search(TagRepository $tagRepo, Request $request)
    {
        $search = $request->query->get('search');  //$_GET['search']
        if(!$search){
            throw $this->createNotFoundException();
        }
        $slugify = new Slugify();
        $slug = $slugify->slugify($search);
        $searchedTags = $tagRepo->searchBySlug($slug);
        $formatedTagsArray = [];
        foreach ($searchedTags as $tag){
            $formatedTagsArray[] = ['name'=> $tag->getName(), 'slug'=> $tag->getSlug()];
        }
        return $this->json($formatedTagsArray);

    }
}
