<?php

namespace Silecust\WebShop\Service\Component\UI\Panel\Session;

use Symfony\Component\HttpFoundation\RequestStack;

readonly class SessionAndMethodChecker
{


    public function __construct(private RequestStack $requestStack)
    {
    }

    public function checkSessionVariablesAndMethod(string $className, string $methodName): bool
    {
        $classQualifierInSession = $this->requestStack->getSession()->get($className);

        $methodQualifierInSession = $this->requestStack->getSession()->get($methodName);

        // return if they don't exist in session
        if (!($classQualifierInSession != null || $methodQualifierInSession != null)) {
            return false;
        }

        // check if they exist in code
        return method_exists(
            $classQualifierInSession,
            $methodQualifierInSession
        );
    }
}