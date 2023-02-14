<?php

namespace App\Controller;

use App\Classe\Search;
use App\Form\SearchType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    #[Route('/nos-produits', name: 'app_products')]
    public function index(ProductRepository $repository, Request $request): Response
    {

        
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            $products = $repository->findWithSearch($search);
        } else {
            $products = $repository->findAll();
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'form' => $form
        ]);
    }

    #[Route('/produit/{slug}', name: 'app_product')]
    public function show($slug, ProductRepository $repository): Response
    {

        $product = $repository->findOneBySlug($slug);

        if(!$product) {
            return $this->redirectToRoute('app_products');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }
}
