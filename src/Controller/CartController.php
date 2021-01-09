<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="Cart")
     */
    public function index(): Response
    {
        $info = $this->setInfo();
        return $this->render('cart/cart.html.twig', [
            'info' => $info
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
