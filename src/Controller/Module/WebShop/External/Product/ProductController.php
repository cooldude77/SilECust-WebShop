<?php

namespace App\Controller\Module\WebShop\External\Product;

use App\Controller\Component\UI\Panel\Components\PanelContentController;
use App\Controller\Component\UI\Panel\Components\PanelHeaderController;
use App\Controller\Component\UI\PanelMainController;
use App\Controller\Module\WebShop\External\Shop\HeaderController;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Service\Module\WebShop\External\Product\ProductFilterSearchInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product/{name}', name: 'web_shop_product_single_display')]
    public function mainPage($name, Request $request):
    Response
    {

        $session = $request->getSession();

        $session->set(
            PanelHeaderController::HEADER_CONTROLLER_CLASS_NAME, HeaderController::class
        );
        $session->set(
            PanelHeaderController::HEADER_CONTROLLER_CLASS_METHOD_NAME,
            'header'
        );
        $session->set(
            PanelContentController::CONTENT_CONTROLLER_CLASS_NAME, self::class
        );
        $session->set(
            PanelContentController::CONTENT_CONTROLLER_CLASS_METHOD_NAME,
            'single'
        );
        $session->set(
            PanelMainController::BASE_TEMPLATE,
            'module/web_shop/external/base/web_shop_base_template.html.twig'
        );

        $request->query->set('name', $name);

        return $this->forward(PanelMainController::class . '::main', ['request' => $request]);


    }

    public function single(ProductRepository $productRepository, Request $request): Response
    {

        $product = $productRepository->findOneBy(['name' => $request->query->get('name')]);
        return $this->render(
            'module/web_shop/external/product/web_shop_single_product.html.twig',
            ['product' => $product]
        );
    }

    public function list(ProductRepository  $productRepository,
                         CategoryRepository $categoryRepository,
                         Request            $request
    ): Response
    {

        if ($request->query->get('category') != null) {

            $category = $categoryRepository->findOneBy(['name' => $request->get('category')]);
            $products = $productRepository->findBy(['category' => $category]);
        } else {
            $products = $productRepository->findAll();
        }

        return $this->render(
            'module/web_shop/external/product/web_shop_product_list.html.twig',
            ['products' => $products]
        );
    }

    /**
     * @param Request $request
     * @param ProductRepository $productRepository
     * @return Response
     * Forwarded in HeaderController of shop
     */
    public function listBySearchTerm(Request                      $request,
                                     ProductRepository            $productRepository,
                                     ProductFilterSearchInterface $productFilterSearch
    ): Response
    {
        $products = $productRepository->search($request->get('searchTerm'));

        return $this->render(
            'module/web_shop/external/product/web_shop_product_list.html.twig',
            ['products' => $products]
        );


    }

    /* @param Request $request
     * @param ProductRepository $productRepository
     * @return Response
     * Forwarded in HeaderController of shop
     */
    public function listByFilter(Request                      $request,
                                   CategoryRepository           $categoryRepository,
                                   ProductFilterSearchInterface $productFilterSearch
    ): Response
    {

        $category = $categoryRepository->findOneBy(['name'=>$request->query->get('category')]);
        $products = $productFilterSearch->searchByFilter($category,json_decode($request->query->get('filter'),true));

        return $this->render(
            'module/web_shop/external/product/web_shop_product_list.html.twig',
            ['products' => $products]
        );


    }


}