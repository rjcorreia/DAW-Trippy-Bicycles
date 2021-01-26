<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductsController
 * @Route("/eshop", name="")
 */
class ProductsController extends AbstractController
{
    /**
     * @Route("/products", name="Products")
     * @param ProductsRepository $productsRepository
     * @param CategoriesRepository $categoriesRepository
     * @param Request $request
     * @return Response
     */
    public function index(ProductsRepository $productsRepository, CategoriesRepository $categoriesRepository)
    {
        $session = new Session();
        $session->start();
        $info = $this->setInfo();
        if ($session->get('bikeCart') == null)
            $cart = [];
        else {
            $cart = $session->get('bikeCart');
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


    /**
     * @Route("/products/updateCart/{productId?}", name="update_cart")
     * @param Request $request
     * @return Response
     */
    function updateCart(Request $request)
    {
        $session = new Session();
        $productId = $request->get('productId');
        if (($productId < 1) || ($productId > 7)) {
            $this->addFlash('error', 'Product does not exist in the database');
            return $this->redirect($this->generateUrl('Products'));
        }
        $cart = $session->get('bikeCart');
        $cart[] = $productId;
        $session->set('bikeCart', $cart);
        return $this->redirect($this->generateUrl('Products'));
    }
}
