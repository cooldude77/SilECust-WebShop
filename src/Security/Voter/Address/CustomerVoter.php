<?php

namespace Silecust\WebShop\Security\Voter\Address;

use Silecust\WebShop\Entity\Customer;
use Silecust\WebShop\Entity\CustomerAddress;
use Silecust\WebShop\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException;
use Silecust\WebShop\Exception\Security\User\UserNotLoggedInException;
use Silecust\WebShop\Security\Voter\VoterConstants;
use Silecust\WebShop\Service\Security\User\Customer\CustomerFromUserFinder;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class CustomerVoter extends Voter
{

    public function __construct(private readonly CustomerFromUserFinder $customerFromUserFinder)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        if (in_array($attribute, [VoterConstants::CREATE, VoterConstants::EDIT, VoterConstants::DISPLAY]))
            if ($subject instanceof CustomerAddress || $subject instanceof Customer)
                if ($this->customerFromUserFinder->isLoggedInUserACustomer())
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
        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case VoterConstants::CREATE:
                try {
                    /** @var Customer $customer */
                    $customer = $subject;

                    return $customer->getId() ==
                        $this->customerFromUserFinder->getLoggedInCustomer()->getId();
                } catch (UserNotAssociatedWithACustomerException|UserNotLoggedInException) {
                    return false;
                }
            case VoterConstants::EDIT:
            case VoterConstants::DISPLAY:

                try {
                    /** @var CustomerAddress $customerAddress */
                    $customerAddress = $subject;
                    return $customerAddress->getCustomer()->getId() ==
                        $this->customerFromUserFinder->getLoggedInCustomer()->getId();
                } catch (UserNotAssociatedWithACustomerException|UserNotLoggedInException) {
                    return false;
                }
        }

        return false;
    }
}
