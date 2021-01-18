<?php

namespace App\Controller;

use App\Repository\OrderItemsRepository;
use App\Repository\OrdersRepository;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        $cartItems = $session->get('bikeCart');
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
                $quantities[] = $quantity;
        }
        $info = $this->setInfo();
        $session->set('cartItems', $cartItems);
        $session->set('quantities', $quantities);
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


    /**
     * @Route("/decrement/product/productId?", name="decrement_product")
     * @param Request $request
     * @param ProductsRepository $productsRepository
     * @return Response
     */
    public function decrementProduct(Request $request, ProductsRepository $productsRepository): Response
    {
        $session = new Session();
        $session->start();
        $productId = $request->get('productId');
        $session = new Session();
        if ($session->get('bikeCart') == null)
            $cart = [];
        else
            $cart = $session->get('bikeCart');
        if ($productId) {
            //reverse array to search backwards so it does not change the order of the cart
            $tempArray = array_reverse($cart);
            $index = array_search($productId, $tempArray);
            unset($tempArray[$index]);
            $session->set('bikeCart', array_reverse($tempArray));
        }
        return $this->redirectToRoute('Cart');
    }


    /**
     * @Route("/eliminate/product/productId?", name="eliminate_product")
     * @param Request $request
     * @return Response
     */
    public function eliminateProduct(Request $request): Response
    {
        $session = new Session();
        $cart = $session->get('bikeCart');
        $productId = array($request->get('productId'));
        $cart = array_diff($cart, $productId);
        $session->set('bikeCart', $cart);
        return $this->redirectToRoute('Cart', [

        ]);
    }

    /**
     * @Route("/increment/product/productId?", name="increment_product")
     * @param Request $request
     * @param ProductsRepository $productsRepository
     * @return Response
     */
    public function incrementProduct(Request $request, ProductsRepository $productsRepository): Response
    {
        $session = new Session();
        $session->start();
        $productId = $request->get('productId');
        $session = new Session();
        if ($session->get('bikeCart') == null)
            $cart = [];
        else
            $cart = $session->get('bikeCart');
        if ($productId) {
            array_push($cart, $productId);
            $session->set('bikeCart', $cart);
        }
        return $this->redirectToRoute('Cart');
    }


    /**
     * @Route("/clear", name="clear_cart")
     */
    public function clearCart(): Response
    {
        $cart = array();
        $session = new Session;
        $session->set('bikeCart', $cart);
        return $this->redirectToRoute('Cart');
    }

    /**
     * @Route("/checkout", name="checkout")
     * @param Request $request
     * @param OrderItemsRepository $orderItemsRepository
     * @param OrdersRepository $ordersRepository
     * @param ProductsRepository $productsRepository
     * @return Response
     */
    public function checkout(Request $request, OrderItemsRepository $orderItemsRepository, OrdersRepository $ordersRepository, ProductsRepository $productsRepository): Response
    {
        $session = new Session;
        $user = $this->getUser();
        if ($user == NULL) {
            $this->addFlash('error', 'You must login in first before you can checkout');
            return $this->redirect($this->generateUrl('Cart'));
        }
        else {
            $this->addFlash('success', 'Order made with success, you will have the ride of your life!');

            $cartItems = $session->get('cartItems');
            $cart= $session->get('cart');
            dump($cartItems);
            $cartItemsQt = array_count_values($cart);
            dump($cartItemsQt);
//            die;
            $ordersRepository->insertOrder($user);
            $orderArray = $ordersRepository->getOrderIdFromUser($user);
//            dump($orderArray);
            $orderArray = array_reverse($orderArray);
            $lastOrder = $orderArray[0]['id'];
//            dump($lastOrder);
            $totalAmount = 0;
            for($i = 0; $i < count($cartItemsQt); ++$i) {
                $currentProduct = $productsRepository->getProductFromId($cartItems[$i]);
                $currentQuantity = $cartItemsQt[$cartItems[$i]];
//                dump($currentQuantity);
                $totalAmount += ($currentProduct->getPrice() * $currentQuantity);
                $orderItemsRepository->insertOrderItem($ordersRepository->getFromOrderId($lastOrder),$currentProduct,$currentQuantity);
            }
            $ordersRepository->updateTotal($totalAmount,$lastOrder);
            $cart = array();
            $session->set('cart',$cart);
            return $this->redirect($this->generateUrl('Home'));
        }
    }
}
