<?php

namespace App\Controller\Component\UI\Panel\Components;

use App\Exception\Component\UI\Panel\Components\ControllerContentClassDoesNotExist;
use App\Exception\Component\UI\Panel\Components\ControllerContentMethodDoesNotExist;
use App\Exception\Component\UI\Panel\Components\NoContentControllerClassProvided;
use App\Exception\Component\UI\Panel\Components\NoContentControllerMethodProvided;
 use App\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Actual functional routes will be called here
 */
class PanelContentController extends EnhancedAbstractController
{
    public const string CONTENT_CONTROLLER_CLASS_NAME = 'CONTENT_CONTROLLER_CLASS_NAME';
    public const string CONTENT_CONTROLLER_CLASS_METHOD_NAME = 'CONTENT_CONTROLLER_CLASS_METHOD_NAME';


    /**
     * @param Request $request
     * @param Session $session
     *
     * @return Response
     * @throws ControllerContentClassDoesNotExist
     * @throws ControllerContentMethodDoesNotExist
     * @throws NoContentControllerClassProvided
     * @throws NoContentControllerMethodProvided
     */
    public function content(Request $request,
        Session $session,
    ): Response {

        $className = $session->get(self::CONTENT_CONTROLLER_CLASS_NAME);
        if ($className == null) {
            throw new NoContentControllerClassProvided();
        }

        $methodName = $session->get(self::CONTENT_CONTROLLER_CLASS_METHOD_NAME);
        if ($methodName == null) {
            throw new NoContentControllerMethodProvided($className);
        }

        if (!class_exists($className)) {
            throw  new ControllerContentClassDoesNotExist($className);
        }
        if (!method_exists($className, $methodName)) {
            throw  new ControllerContentMethodDoesNotExist($className, $methodName);
        }

        $response = $this->forward(
            $session->get(self::CONTENT_CONTROLLER_CLASS_NAME)
            . "::"
            . $session->get(self::CONTENT_CONTROLLER_CLASS_METHOD_NAME),
            ['request' => $request]
        );

        // clear session variables after content has been retrieved
        $session->set(self::CONTENT_CONTROLLER_CLASS_NAME, null);
        $session->set(self::CONTENT_CONTROLLER_CLASS_METHOD_NAME, null);

        return $response;

    }

}