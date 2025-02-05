<?php

namespace App\Service\Component\Controller;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

readonly class ViewHelper
{

    public function __construct(private Environment $environment)
    {

    }

    /**
     * @param $view
     * @param array $parameters
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function render($view, array $parameters): Response
    {
        return $this->doRender($view, $parameters);
    }

    public function getTwig(): Environment
    {
        return $this->environment;
    }
    private function doRender(string $view, ?string $block, array $parameters, ?Response $response, string $method): Response
    {
        $content = $this->doRenderView($view, $block, $parameters, $method);
        $response ??= new Response();

        if (200 === $response->getStatusCode()) {
            foreach ($parameters as $v) {
                if ($v instanceof FormInterface && $v->isSubmitted() && !$v->isValid()) {
                    $response->setStatusCode(422);
                    break;
                }
            }
        }

        $response->setContent($content);

        return $response;
    }
    private function doRenderView(string $view, ?string $block, array $parameters, string $method): string
    {
        if (!$this->container->has('twig')) {
            throw new \LogicException(\sprintf('You cannot use the "%s" method if the Twig Bundle is not available. Try running "composer require symfony/twig-bundle".', $method));
        }

        foreach ($parameters as $k => $v) {
            if ($v instanceof FormInterface) {
                $parameters[$k] = $v->createView();
            }
        }

        if (null !== $block) {
            return $this->container->get('twig')->load($view)->renderBlock($block, $parameters);
        }

        return $this->container->get('twig')->render($view, $parameters);
    }


}