<?php

namespace Silecust\WebShop\Controller\Module\WebShop\External\Product;

use Knp\Component\Pager\PaginatorInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Controller\Module\WebShop\External\Common\Components\HeadController;
use Silecust\WebShop\Controller\Module\WebShop\External\Common\Components\HeaderController;
use Silecust\WebShop\Controller\Module\WebShop\External\Common\Components\SideBarController;
use Silecust\WebShop\Event\Module\WebShop\External\Product\ProductListingQueryEvent;
use Silecust\WebShop\Form\Module\WebShop\External\Product\WebShopProductSorter;
use Silecust\WebShop\Repository\ProductRepository;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelContentController;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelHeadController;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelHeaderController;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelSideBarController;
use Silecust\WebShop\Service\Component\UI\Panel\PanelMainController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends EnhancedAbstractController
{


    #[Route('/product/{name}', name: 'sc_web_shop_product_single_display')]
    public function mainPage($name, Request $request):
    Response
    {

        $session = $request->getSession();

        $session->set(
            PanelHeadController::HEAD_CONTROLLER_CLASS_NAME, HeadController::class
        );
        $session->set(
            PanelHeadController::HEAD_CONTROLLER_CLASS_METHOD_NAME,
            'head'
        );

        $session->set(
            PanelHeaderController::HEADER_CONTROLLER_CLASS_NAME, HeaderController::class
        );
        $session->set(
            PanelHeaderController::HEADER_CONTROLLER_CLASS_METHOD_NAME,
            'header'
        );

        $session->set(
            PanelSideBarController::SIDE_BAR_CONTROLLER_CLASS_NAME, SideBarController::class
        );
        $session->set(
            PanelSideBarController::SIDE_BAR_CONTROLLER_CLASS_METHOD_NAME,
            'sideBar'
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
            '@SilecustWebShop/module/web_shop/external/product/page/single_product_display_page.html.twig'
        );

        $request->query->set('name', $name);

        return $this->forward(PanelMainController::class . '::main', ['request' => $request]);


    }

    public function single(ProductRepository $productRepository, Request $request): Response
    {

        $product = $productRepository->findOneBy(['name' => $request->query->get('name')]);
        return $this->render(
            '@SilecustWebShop/module/web_shop/external/product/single_product.html.twig',
            ['product' => $product]
        );
    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function list(EventDispatcherInterface $eventDispatcher,
                         PaginatorInterface       $paginator,
                         Request                  $request
    ): Response
    {


        $event = new ProductListingQueryEvent($request);
        $eventDispatcher->dispatch($event, ProductListingQueryEvent::LIST_QUERY_EVENT);

        $pagination = $paginator->paginate(
            $event->getQuery(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        $sortForm = $this->createForm(WebShopProductSorter::class);

        $sortForm->handleRequest($request);


        if ($sortForm->isSubmitted() && $sortForm->isValid()) {

            $queryParams = $this->mergeQueryParameters($request, $sortForm);

            return $this->redirectToRoute('sc_home', $queryParams);
        }

        return $this->render(
            '@SilecustWebShop/module/web_shop/external/product/product_list.html.twig',
            ['pagination' => $pagination,
                'sortForm' => $sortForm]
        );
    }

    public function listBySearchTerm(Request $request, ProductRepository $productRepository
    ): Response
    {
        $products = $productRepository->search($request->get('searchTerm'));

        return $this->render(
            '@SilecustWebShop/module/web_shop/external/product/product_list.html.twig',
            ['products' => $products]
        );


    }

    /**
     * @param Request $request
     * @param FormInterface $sortForm
     * @return array
     */
    public function mergeQueryParameters(Request $request, FormInterface $sortForm): array
    {
        $queryParams = $request->query->all();

        $queryParams['sort_by'] =
            $sortForm->get('sort_by')->getData();
        $queryParams['order'] =
            $sortForm->get('order')->getData();
        return $queryParams;
    }

}