<?php

namespace App\Service\Component\Controller;

use Symfony\Component\HttpFoundation\Response;

class TwigPreRenderEvent
{

    /**
     * @param string $view
     * @param array $parameters
     * @param Response|null $response
     */
    public function __construct(private string $view, private array $parameters, private ?Response $response)
    {
    }

    public function getView(): string
    {
        return $this->view;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getResponse(): ?Response
    {
        return $this->response;
    }

    public function setView(string $view): void
    {
        $this->view = $view;
    }

    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    public function setResponse(?Response $response): void
    {
        $this->response = $response;
    }

}