<?php

namespace App\Service\Component\UI\Panel;

use Symfony\Component\HttpFoundation\RequestStack;

class SessionAndMethodChecker
{


    public function __construct(private readonly RequestStack $requestStack)
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
        $c = method_exists(
            $this->requestStack->getSession()->get($className),
            $this->requestStack->getSession()->get($methodName)
        );


        return $c;
    }
}