<?php

namespace Silecust\WebShop\Controller\Admin\Employee\FrameWork;

use Exception;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Exception\Admin\Employee\FrameWork\AdminUrlFunctionKeyParameterNull;
use Silecust\WebShop\Exception\Admin\Employee\FrameWork\AdminUrlTypeKeyParameterNull;
use Silecust\WebShop\Exception\Admin\SideBar\Action\EmptyActionListMapException;
use Silecust\WebShop\Exception\Admin\SideBar\Action\FunctionNotFoundInMap;
use Silecust\WebShop\Exception\Admin\SideBar\Action\TypeNotFoundInMap;
use Silecust\WebShop\Service\Admin\Employee\Route\AdminRoutingFromRequestFinder;
use Silecust\WebShop\Service\Admin\SideBar\Action\PanelActionListMapBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

/**
 *
 */
class ContentController extends EnhancedAbstractController
{

    /**
     * @param Request                   $request
     * @param RouterInterface           $router
     * @param PanelActionListMapBuilder $builder
     *
     * @return Response
     * @throws AdminUrlFunctionKeyParameterNull
     * @throws AdminUrlTypeKeyParameterNull
     * @throws EmptyActionListMapException
     * @throws FunctionNotFoundInMap
     * @throws TypeNotFoundInMap
     */
    public function content(Request $request,
        AdminRoutingFromRequestFinder $adminRoutingFromRequestFinder
    ): Response {

        if ($request->query->get('_function') == 'dashboard') {
            return $this->render(
                '@SilecustWebShop/admin/employee/dashboard/dashboard.html.twig',
                ['request' => $request]
            );
        }

        $parameterObject = $adminRoutingFromRequestFinder->getAdminRouteObject($request);


        $response = $this->forward(
            $parameterObject->getControllerAction(),
            $parameterObject->getParams(),
            $request->query->all()
        );


        $content = $response->getContent();

        try {
            // if the content is a twig template, unserialize will throw exception
            $unserialized = unserialize($content);

            if (!empty($unserialized['id'])) {
                $success_url = $request->get('_redirect_upon_success_url') . "&id="
                    . $unserialized['id'];
                return $this->redirect($success_url);
            }
        } catch (Exception $e) {
            // do nothing
        }

        return $this->render(
            '@SilecustWebShop/admin/ui/panel/section/content/content.html.twig', ['content' => $content]
        );

    }
}