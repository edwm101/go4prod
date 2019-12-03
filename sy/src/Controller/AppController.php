<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\Products1Type;
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
     * @Route("/", name="products_index", methods={"GET"})
     */
    public function index(): Response
    {
        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', self::API_URL . 'products/all?max=10');
        $products = $response->toArray();
        $products = $products["items"];
        return $this->render('index.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/shop/", name="shop_show", methods={"GET"})
     */
    public function shop(Request $request): Response
    {
        $q = $request->query->get('q');
        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', self::API_URL . 'products/find/?q='.$q);
        $products = $response->toArray();
        $products = $products;
        return $this->render('shop.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/products/{id}", name="products_show", methods={"GET"})
     */
    public function show($id): Response
    {
        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', self::API_URL . 'products?id=' . $id);
        $product = $response->toArray();
        $product = $product["info"];
        return $this->render('show.html.twig', [
            'product' => $product,
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
