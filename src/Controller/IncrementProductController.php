<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class IncrementProductController extends AbstractController
{
    /**
     * @Route("/increment/product/productId?", name="increment_product")
     * @param Request $request
     * @param ProductsRepository $productsRepository
     * @return Response
     */
    public function index(Request $request, ProductsRepository $productsRepository): Response
    {
        $session = new Session();
        $session->start();
        $productId = $request->get('productId');
        $session = new Session();
        if ($session->get('cart') == null)
            $cart = [];
        else
            $cart = $session->get('cart');
        if ($productId) {
            array_push($cart, $productId);
            $session->set('cart', $cart);
        }
        return $this->redirectToRoute('Cart');
    }

}
