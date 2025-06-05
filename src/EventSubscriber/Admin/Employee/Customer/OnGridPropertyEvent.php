<?php

namespace Silecust\WebShop\EventSubscriber\Admin\Employee\Customer;

use Silecust\WebShop\Controller\MasterData\Customer\CustomerController;
use Silecust\WebShop\Event\Component\UI\Panel\List\GridPropertyEvent;
use Silecust\WebShop\Service\Component\Event\EventRouteChecker;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

readonly class OnGridPropertyEvent implements EventSubscriberInterface
{
    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(private readonly EventRouteChecker $eventRouteChecker
    )
    {
    }
    public static function getSubscribedEvents(): array
    {
        return [
            GridPropertyEvent::EVENT_NAME => 'setProperty'
        ];

    }

    public function setProperty(GridPropertyEvent $event): void
    {

        if (!
        $this->eventRouteChecker->isInRouteList($event->getRequest(), ['sc_admin_panel', 'sc_admin_customer_list']))
            return;
        if ($this->eventRouteChecker->isAdminRoute($event->getRequest()))
            if (!$this->eventRouteChecker->checkFunctions($event->getRequest(), ['customer']))
                return;

        // for testing and UI both

        if ($event->getData()['event_caller'] != CustomerController::LIST_IDENTIFIER)
            return;

        $event->setListGridProperties([
            'title' => 'Customer',
            'link_id' => 'id-customer',
            'config' => [
                'create_link' => [
                    'link_id' => ' id-create-customer',
                    'route' => 'sc_customer_create',
                    'anchorText' => 'Create customer',
                    'redirect_upon_success_route' => 'sc_admin_customer_list'
                ],
                'edit_link' => [
                    'link_id' => ' id-edit-customer',
                    'route' => 'sc_customer_edit',
                    'anchorText' => 'Edit',
                    'redirect_upon_success_route' => 'sc_admin_customer_list'
                ],
                'display_link' => [
                    'link_id' => ' id-display-customer',
                    'route' => 'sc_customer_display',
                    'anchorText' => 'Display',
                    'redirect_upon_success_route' => 'sc_admin_customer_list'
                ]
            ],
            'columns' => [
                [
                    'label' => 'First Name',
                    'propertyName' => 'firstName',
                    'action' => 'display',
                ],
                [
                    'label' => 'Middle Name',
                    'propertyName' => 'middleName'
                ],
                [
                    'label' => 'Last Name',
                    'propertyName' => 'lastName'
                ],
                [
                    'label' => 'Given Name',
                    'propertyName' => 'givenName'
                ]
            ],


        ]);
    }
}