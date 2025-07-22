<?php

namespace Silecust\WebShop\Security\Voter\Order\Header;

use Silecust\WebShop\Entity\OrderHeader;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class EmployeeVoter extends Voter
{
    const string EDIT = 'EDIT';
    const string VIEW = 'VIEW';

    public function __construct(
        private AccessDecisionManagerInterface $accessDecisionManager,
    )
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof OrderHeader;
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
