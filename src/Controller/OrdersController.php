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
    public function index(OrdersRepository $ordersRepository, OrderItemsRepository $orderItemsRepository): Response
    {
        $session = new Session();
        $cartItems = $session->get('cart');
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'Forbidden, you must login first');
            return $this->redirect($this->generateUrl('Home'));
        }
        $products = $ordersRepository->getFromId($user);
        $items = array();
        foreach ($products as $product) {
            $items = array_merge($items, $orderItemsRepository->findByExampleField($product->getId()));
        }
        $info = $this->setInfo();
        return $this->render('orders/index.html.twig', [
            'info' => $info,
            'cart' => $cartItems,
            'orders' => $products,
            'orderItems' => $items
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
