<?php

namespace App\Controller\Admin\Employee\FrameWork;

use App\Service\Admin\Action\PanelActionListMapBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RouterInterface;

class ContentController extends AbstractController
{

    public function content(Request $request, RouterInterface $router,
        PanelActionListMapBuilder $builder,
    ): Response {

        // todo: check context route
        // todo: make a class for getting system session variables ?

        if ($request->get('_function') == 'dashboard') {
            return $this->render(
                'admin/employee/dashboard/dashboard.html.twig',
                ['request' => $request]
            );
        }

        if ($request->get('_function') != null) {
            $function = $request->get('_function');

            $type = $request->get('_type');

            // special case when not calling any function, goto home
            if ($function == null && $type == null) {
                return $this->render(
                    'admin/ui/panel/section/content/content.html.twig',
                    ['content' => "This is home"]
                );
            }

            $routeName = $builder->build()->getPanelActionListMap()->getRoute(
                $function, $type
            );

            // call controller
            $callRoute = $router->getRouteCollection()->get($routeName);

            if ($callRoute == null) {
                throw  new RouteNotFoundException($routeName);
            }

            $controllerAction = $callRoute->getDefault('_controller');
            $params = ['request' => $request];
            if (!empty($request->get('id'))) {
                $params['id'] = $request->get('id');
            }

            $response = $this->forward(
                $controllerAction, $params, $request->query->all()
            );

        }

        $content = $response->getContent();

        try {
            // if the content is a twig template, unserialize will throw exception
            $unserialized = unserialize($content);

            if (!empty($unserialized['id'])) {
                $success_url = $request->get('_redirect_upon_success_url') . "&id="
                    . $unserialized['id'];
                return $this->redirect($success_url);
            }
        } catch (\Exception $e) {
            // do nothing
        }
        return $this->render(
            'admin/ui/panel/section/content/content.html.twig', ['content' => $content]
        );
    }

}