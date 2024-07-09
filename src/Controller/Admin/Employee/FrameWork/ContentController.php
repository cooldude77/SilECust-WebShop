<?php

namespace App\Controller\Admin\Employee\FrameWork;

use App\Exception\Admin\Employee\FrameWork\AdminUrlFunctionKeyParameterNull;
use App\Exception\Admin\Employee\FrameWork\AdminUrlTypeKeyParameterNull;
use App\Service\Admin\Action\Exception\EmptyActionListMapException;
use App\Service\Admin\Action\Exception\FunctionNotFoundInMap;
use App\Service\Admin\Action\Exception\TypeNotFoundInMap;
use App\Service\Admin\Action\PanelActionListMapBuilder;
use App\Service\Admin\Employee\FrameWork\AdminRoutingFromRequestFinder;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

/**
 *
 */
class ContentController extends AbstractController
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
    public function content(Request $request, RouterInterface $router,
        PanelActionListMapBuilder $builder,
        AdminRoutingFromRequestFinder $adminRoutingFromRequestFinder
    ): Response {

        // todo: check context route
        // todo: make a class for getting system session variables ?

        $parameterObject = $adminRoutingFromRequestFinder->getAdminRouteObject($request);

        if ($request->query->get('_function') == 'dashboard') {
            return $this->render(
                'admin/employee/dashboard/dashboard.html.twig',
                ['request' => $request]
            );
        }


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
            'admin/ui/panel/section/content/content.html.twig', ['content' => $content]
        );

    }
}