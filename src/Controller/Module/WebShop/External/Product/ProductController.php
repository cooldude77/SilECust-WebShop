<?php

namespace App\Controller\Module\WebShop\External\Product;

use App\Controller\Component\UI\Panel\Components\PanelContentController;
use App\Controller\Component\UI\Panel\Components\PanelHeaderController;
use App\Controller\Component\UI\PanelMainController;
use App\Controller\Module\WebShop\External\Shop\HeaderController;
use App\Event\Module\WebShop\External\Product\ProductListingQueryEvent;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\Query\QueryException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    public function __construct()
    {

    }

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

    /**
     * @param ProductRepository $productRepository
     * @param CategoryRepository $categoryRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     * @throws QueryException
     */
    public function list(EventDispatcherInterface $eventDispatcher,
                         PaginatorInterface       $paginator,
                         Request                  $request
    ): Response
    {


        $event = new ProductListingQueryEvent($request);
        $eventDispatcher->dispatch($event, ProductListingQueryEvent::LIST_QUERY_EVENT);

        $query = $event->getQuery()->getResult();
        $pagination = $paginator->paginate(
            $event->getQuery(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render(
            'module/web_shop/external/product/web_shop_product_list.html.twig',
            ['pagination' => $pagination]
        );
    }

    public function listBySearchTerm(Request $request, ProductRepository $productRepository
    ): Response
    {
        $products = $productRepository->search($request->get('searchTerm'));

        return $this->render(
            'module/web_shop/external/product/web_shop_product_list.html.twig',
            ['products' => $products]
        );


    }

}