<?php

namespace App\Controller;

use App\Repository\OrderItemsRepository;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="Cart")
     * @param ProductsRepository $productsRepository
     * @param OrderItemsRepository $orderItemsRepository
     * @return Response
     */
    public function index(ProductsRepository $productsRepository, OrderItemsRepository $orderItemsRepository): Response
    {
        $session = new Session();
        $cartItems = $session->get('cart');
        $cart = array();
        $quantities = array();
        if ($cartItems) {
            $cartItemsQt = array_count_values($cartItems);
            $cartItems = array_keys($cartItemsQt);
            foreach ($cartItems as $item) {
                $product = $productsRepository->getFromId($item);
                $cart = array_merge($cart, $product);
            }
            foreach ($cartItemsQt as $quantity)
                $quantities[]= $quantity;
        }
        $info = $this->setInfo();
        return $this->render('cart/cart.html.twig', [
            'info' => $info,
            'cart' => $cartItems,
            'cartItems' => $cart,
            'cartQt' => $quantities,
        ]);
    }


    function setInfo(): array
    {
        $info['menu0'] = 'Home';
        $info['menu1'] = "Login";
        $info['menu2'] = "Register";
        $info['menu3'] = "Cart";
        $info['uName'] = "";
        $info['uId'] = "";

        if ($this->getUser()) {
            $info['uName'] = $this->getUser()->getUsername();
            $info['uId'] = $this->getUser()->getId();
            $info['menu1'] = "Logout";
            $info['menu2'] = "Orders";

        }

        return $info;
    }
}
