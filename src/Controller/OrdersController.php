<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrdersController extends AbstractController
{
    /**
     * @Route("/orders", name="Orders")
     */
    public function index(): Response
    {
        $info = $this->setInfo();
        return $this->render('orders/index.html.twig', [
            'info' => $info
        ]);
    }


    function setInfo(): array
    {
        $info['menu0'] = 'Home';
        $info['menu1'] = "Logout";
        $info['menu2'] = "Orders";
        $info['menu3'] = "Cart";
        $info['uName'] = $this->getUser()->getUsername();
        $info['uId'] = $this->getUser()->getId();


        return $info;
    }
}
