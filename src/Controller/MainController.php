<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/EShop", name="Home")
     */
    public function index(): Response
    {
        $session = new Session();
        $cartItems = $session->get('bikeCart');
        $info = $this->setInfo();
        return $this->render('main/index.html.twig', [
            'info' => $info,
            'cart' => $cartItems
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
