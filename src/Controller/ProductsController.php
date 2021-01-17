<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    /**
     * @Route("/products/productId?", name="Products")
     * @param ProductsRepository $productsRepository
     * @param CategoriesRepository $categoriesRepository
     * @param Request $request
     * @return Response
     */
    public function index(Request $request, ProductsRepository $productsRepository, CategoriesRepository $categoriesRepository): Response
    {
        $session = new Session();
        $session->start();
        $info = $this->setInfo();
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
        $products = $productsRepository->getAllProducts();
        $categories = $categoriesRepository->getAllCategories();
        return $this->render('products/index.html.twig', [
            'info' => $info,
            'products' => $products,
            'categories' => $categories,
            'cart' => $cart
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


    function updateCart()
    {

    }
}
