<?php

namespace App\Service\Component\UI\Panel;

use Symfony\Component\HttpFoundation\RequestStack;

readonly class SessionAndMethodChecker
{


    public function __construct(private RequestStack $requestStack)
    {
    }

    public function checkSessionVariablesAndMethod(string $className, string $methodName): bool
    {
        $a = $this->requestStack->getSession()->get($className) != null;

        $b = $this->requestStack->getSession()->get($methodName) != null;

        // return if they don't exist in session
        if (!($a || $b)) {
            return false;
        }

        // check if they exist in code
        return method_exists(
            $this->requestStack->getSession()->get($className),
            $this->requestStack->getSession()->get($methodName)
        );
    }
}