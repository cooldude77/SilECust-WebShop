<?php /** @noinspection ALL */

namespace Silecust\WebShop\EventSubscriber\Admin\Employee\Customer\Address\Display;

use Silecust\WebShop\Event\Component\UI\Panel\Display\DisplayParamPropertyEvent;
use Silecust\WebShop\Service\Admin\Employee\Route\AdminRoutingFromRequestFinder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

readonly class OnDisplayParamPropertyEvent implements EventSubscriberInterface
{
    /**
     * @param RouterInterface $router
     */
    public function __construct(
        private readonly RouterInterface               $router,
        private readonly AdminRoutingFromRequestFinder $adminRoutingFromRequestFinder
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            DisplayParamPropertyEvent::EVENT_NAME => 'setProperty'
        ];

    }

    /**
     * @param DisplayParamPropertyEvent $event
     * @return void
     * @throws \Silecust\WebShop\Exception\Admin\Employee\FrameWork\AdminUrlFunctionKeyParameterNull
     * @throws \Silecust\WebShop\Exception\Admin\Employee\FrameWork\AdminUrlTypeKeyParameterNull
     * @throws \Silecust\WebShop\Exception\Admin\SideBar\Action\EmptyActionListMapException
     * @throws \Silecust\WebShop\Exception\Admin\SideBar\Action\FunctionNotFoundInMap
     * @throws \Silecust\WebShop\Exception\Admin\SideBar\Action\TypeNotFoundInMap
     */
    public function setProperty(DisplayParamPropertyEvent $event): void
    {


        $route = $this->router->match($event->getRequest()->getPathInfo());

        // To check for both stand-alone test case where admin panel is not called
        // and also where admin panel is called from UI
        if (!in_array($route['_route'], ['sc_admin_panel', 'sc_admin_address_display']))
            return;

        // if admin panel is called
        // check proper function
        if (in_array($route['_route'], ['sc_admin_panel'])) {
            $object = $this->adminRoutingFromRequestFinder->getAdminRouteObject($event->getRequest());

            if ($object->getFunction() != 'customer_address')
                return;

        }


        // @formatter: off
        $event->setDisplayParamProperties(
            [
                'title' => 'Customer Address',
                'link_id' => 'id-customer-address',
                'config' => [
                    'edit_link' => [
                        'editButtonLinkText' => 'Edit',
                        'route' => 'sc_my_address_edit',
                        'link_id' => 'id-display-customer-address']
                ],
                'fields' => [
                    [
                        'label' => 'line 1',
                        'propertyName' => 'line1',
                    ], [
                        'label' => 'line 2',
                        'propertyName' => 'line2',
                    ], [
                        'label' => 'line 3',
                        'propertyName' => 'line3',
                    ],
                ]]
        );
        // @formatter: on
    }

}