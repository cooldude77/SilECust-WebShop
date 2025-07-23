<?php

namespace Silecust\WebShop\Security\Voter\Order\Header;

use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException;
use Silecust\WebShop\Exception\Security\User\UserNotLoggedInException;
use Silecust\WebShop\Service\Security\User\Customer\CustomerFromUserFinder;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class CustomerVoter extends Voter
{
    const string EDIT = 'EDIT';
    const string DISPLAY = 'DISPLAY';

    public function __construct(private readonly CustomerFromUserFinder $customerFromUserFinder)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::DISPLAY])
            && $subject instanceof OrderHeader
            && $this->customerFromUserFinder->isLoggedInUserACustomer();
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        /** @var OrderHeader $orderHeader */
        $orderHeader = $subject;
        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                return false;
            case self::DISPLAY:
                try {
                    return $orderHeader->getCustomer()->getId() ==
                        $this->customerFromUserFinder->getLoggedInCustomer()->getId();
                } catch (UserNotAssociatedWithACustomerException|UserNotLoggedInException ) {
                    return false;
                }
        }

        return false;
    }
}
