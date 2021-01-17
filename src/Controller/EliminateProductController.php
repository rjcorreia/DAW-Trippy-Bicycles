<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class EliminateProductController extends AbstractController
{
    /**
     * @Route("/eliminate/product/productId?", name="eliminate_product")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $session = new Session();
        $cart = $session->get('cart');
        $productId = array($request->get('productId'));
        $cart = array_diff($cart,$productId);
        $session->set('cart',$cart);
        return $this->redirectToRoute('Cart', [

        ]);
    }
}
