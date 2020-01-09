<?php

namespace App\Controller;

use App\Entity\Products;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpClient\HttpClient;

/**
 * @Route("/")
 */
class AppController extends AbstractController
{
    const API_URL = "http://localhost/go4prod/api/v2/public/";

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findProducts(10);
        return $this->render('index.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/shop/", name="shop_show", methods={"GET"})
     */
    public function shop( Request $request, ProductRepository $productRepository): Response
    {

        // if (!$this->getUser()) return $this->redirectToRoute('index');
        
        $q = $request->query->get('q') ?? '';
        // $httpClient = HttpClient::create();
        // $response = $httpClient->request('GET', self::API_URL . 'products/find/?q=' . $q);
        // $products = $response->toArray();
        // $products = $products;
        $products = $productRepository->findProducts(24, $q);

        return $this->render('shop.html.twig', [
            'products' => $products,
            'query' => $q
        ]);
    }

    /**
     * @Route("/product/{id}", name="product_show", methods={"GET"})
     */
    public function show($id): Response
    {
        // $httpClient = HttpClient::create();
        // $response = $httpClient->request('GET', self::API_URL . 'products?id=' . $id);
        // $product = $response->toArray();
        // $product = $product["info"];

        $product = $this->getDoctrine()->getRepository(Products::class)->find($id);
        $product->setDescription(str_replace("Soyez le premier à donner votre avis !", "", $product->getDescription()));
        $images = explode(",", str_replace('small_default', 'large_default', $product->getImages()));

        return $this->render('show.html.twig', [
            'product' => $product,
            'images' => $images
        ]);
    }
    /**
     * @Route("user/account_index", name="account_index", methods={"GET"})
     */
    public function account_index(): Response
    {
        return $this->render('users/account.html.twig', [
            'products' => "",
        ]);
    }
}
