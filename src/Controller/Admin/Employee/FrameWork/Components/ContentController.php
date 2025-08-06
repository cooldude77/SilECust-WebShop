<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace Silecust\WebShop\Controller\Admin\Employee\FrameWork\Components;

use Exception;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Exception\Admin\Employee\FrameWork\AdminUrlFunctionKeyParameterNull;
use Silecust\WebShop\Exception\Admin\Employee\FrameWork\AdminUrlFunctionKeyParameterNull as AdminUrlFunctionKeyParameterNullAlias;
use Silecust\WebShop\Exception\Admin\Employee\FrameWork\AdminUrlTypeKeyParameterNull;
use Silecust\WebShop\Exception\Admin\Employee\FrameWork\Components\RedirectUponSuccessUrlIssue;
use Silecust\WebShop\Exception\Admin\Employee\FrameWork\Components\ResponseFromControllerInvalid;
use Silecust\WebShop\Exception\Admin\SideBar\Action\EmptyActionListMapException;
use Silecust\WebShop\Exception\Admin\SideBar\Action\FunctionNotFoundInMap;
use Silecust\WebShop\Exception\Admin\SideBar\Action\TypeNotFoundInMap;
use Silecust\WebShop\Service\Admin\Employee\Route\AdminRoutingFromRequestFinder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 */
class ContentController extends EnhancedAbstractController
{

    /**
     * @param Request $request
     * @param AdminRoutingFromRequestFinder $adminRoutingFromRequestFinder
     * @return Response
     * @throws AdminUrlFunctionKeyParameterNullAlias
     * @throws \Silecust\WebShop\Exception\Admin\Employee\FrameWork\AdminUrlTypeKeyParameterNull
     * @throws \Silecust\WebShop\Exception\Admin\Employee\FrameWork\Components\RedirectUponSuccessUrlIssue
     * @throws \Silecust\WebShop\Exception\Admin\Employee\FrameWork\Components\ResponseFromControllerInvalid
     * @throws \Silecust\WebShop\Exception\Admin\SideBar\Action\EmptyActionListMapException
     * @throws \Silecust\WebShop\Exception\Admin\SideBar\Action\FunctionNotFoundInMap
     * @throws \Silecust\WebShop\Exception\Admin\SideBar\Action\TypeNotFoundInMap
     */
    public function content(Request                       $request,
                            AdminRoutingFromRequestFinder $adminRoutingFromRequestFinder
    ): Response
    {

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

        if ($response instanceof JsonResponse)
            throw new ResponseFromControllerInvalid("The response is a JsonResponse Object. The response should be a Response object with serialized values in case of success");


        $content = $response->getContent();

        // if the content is a twig template, unserialize will return false
        $unserialized = @unserialize($content);

        if ($unserialized === false)
            return $this->render(
                '@SilecustWebShop/admin/ui/panel/section/content/content.html.twig', ['content' => $content]
            );

        if (!empty($unserialized['id'])) {
            $success_url = $request->get('_redirect_upon_success_url') . "&id=" . $unserialized['id'];
            return $this->redirect($success_url);
        } else if (!empty($request->get('_redirect_upon_success_url'))) {
            $success_url = $request->get('_redirect_upon_success_url');
            return $this->redirect($success_url);
        }
        throw new RedirectUponSuccessUrlIssue($request);

    }
}