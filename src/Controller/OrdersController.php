<?php

namespace App\Controller;

use App\Repository\OrderItemsRepository;
use App\Repository\OrdersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class OrdersController extends AbstractController
{
    /**
     * @Route("/orders", name="Orders")
     * @param OrdersRepository $ordersRepository
     * @return Response
     */
    public function index(OrdersRepository $ordersRepository): Response
    {
        $session = new Session();
        $cartItems = $session->get('cart');
        $user = $this->getUser();
        $orders = $ordersRepository->getFromId($user);
        $info = $this->setInfo();
        return $this->render('orders/index.html.twig', [
            'info' => $info,
            'cart' => $cartItems,
            'orders' => $orders
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
