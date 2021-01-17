<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class ClearCartController extends AbstractController
{
    /**
     * @Route("/clear", name="clear_cart")
     */
    public function index(): Response
    {
        $cart = array();
        $session = new Session;
        $session->set('cart',$cart);
        return $this->redirectToRoute('Cart');
    }
}
