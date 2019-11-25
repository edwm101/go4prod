<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\Products1Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpClient\HttpClient;

/**
 * @Route("/")
 */
class AppController extends AbstractController
{
    /**
     * @Route("/", name="products_index", methods={"GET"})
     */
    public function index(): Response
    {

        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', 'https://api.github.com/repos/symfony/symfony-docs');
        $products = $this->getDoctrine()
            ->getRepository(Products::class)
            ->findBy([], ['id' => 'DESC'], 14);

        return $this->render('index.html.twig', [
            'products' => $products,
        ]);
    }
    /**
     * @Route("user/account_index", name="account_index", methods={"GET"})
     */
    public function account_index(): Response
    {

        $products = $this->getDoctrine()
            ->getRepository(Products::class)
            ->findBy([], ['id' => 'DESC'], 14);

        return $this->render('users/account.html.twig', [
            'products' => $products,
        ]);
    }
    /**
     * @Route("/products/{id}", name="products_show", methods={"GET"})
     */
    public function show(Products $product): Response
    {
        return $this->render('products/show.html.twig', [
            'product' => $product,
        ]);
    }
}
