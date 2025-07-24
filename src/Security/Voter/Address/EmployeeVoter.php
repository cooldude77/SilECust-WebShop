<?php

namespace Silecust\WebShop\Security\Voter\Address;

use Silecust\WebShop\Entity\Customer;
use Silecust\WebShop\Entity\CustomerAddress;
use Silecust\WebShop\Security\Voter\VoterConstants;
use Silecust\WebShop\Service\Security\User\Employee\EmployeeFromUserFinder;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class EmployeeVoter extends Voter
{


    public function __construct(
        private readonly AccessDecisionManagerInterface $accessDecisionManager,
        private readonly EmployeeFromUserFinder         $employeeFromUserFinder
    )
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        if (in_array($attribute, [VoterConstants::CREATE, VoterConstants::EDIT, VoterConstants::DISPLAY]))
            if ($subject instanceof CustomerAddress || $subject instanceof Customer)
                if ($this->employeeFromUserFinder->isLoggedInUserAnEmployee())
                    return true;

        return false;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        if ($this->accessDecisionManager->decide($token, ['ROLE_EMPLOYEE'])) {
            return true;
        }
        return false;
    }
}
