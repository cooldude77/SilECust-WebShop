<?php

namespace App\Service\Component\Controller;

use Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;

readonly class HttpKernelHelper
{

    public function __construct(private readonly RequestStack $requestStack, private HttpKernel $httpKernel)
    {
    }

    /**
     * @throws Exception
     */
    public function forward(string $controller, array $path = [], array $query = []): Response
    {
        $request = $this->requestStack->getCurrentRequest();
        $path['_controller'] = $controller;
        $subRequest = $request->duplicate($query, null, $path);

        return $this->httpKernel->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
    }

    /**
     * Returns a RedirectResponse to the given URL.
     *
     * @param int $status The HTTP status code (302 "Found" by default)
     */
    public function redirect(string $url, int $status = 302): RedirectResponse
    {
        return new RedirectResponse($url, $status);
    }
}