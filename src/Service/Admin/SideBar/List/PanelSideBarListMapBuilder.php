<?php

namespace App\Service\Admin\SideBar\List;

class PanelSideBarListMapBuilder
{

    public function build(string $adminUrl): PanelSideBarListMap
    {
        return new PanelSideBarListMap(

            [
                'sections' =>
                    [
                        [
                            'id' => 'categories',
                            'header_text' => 'Categories',
                            'items' => [
                                [
                                    'id' => 'category-list',
                                    'url' => $this->appendForAdmin(
                                        $adminUrl,
                                        'category'
                                    ),
                                    'text' => 'Categories',
                                    'css-id' => 'sidebar-link-category-list'
                                ],
                            ],
                            'roles' => ['ROLE_EMPLOYEE'],
                        ],
                        [
                            'id' => 'products',
                            'header_text' => 'Products',
                            'items' => [
                                [
                                    'id' => 'product-list',
                                    'url' => $this->appendForAdmin(
                                        $adminUrl,
                                        'product'
                                    ),
                                    'text' => 'Products',
                                    'css-id' => 'sidebar-link-product-list'
                                ],
                                [
                                    'id' => 'price_product_base-list',
                                    'url' => $this->appendForAdmin(
                                        $adminUrl,
                                        'price_product_base'
                                    ),
                                    'text' => 'Base price',
                                    'css-id' => 'sidebar-link-base-price-list'
                                ],
                                // todo: implement
                                /* [
                                     'id' => 'product-type-list',
                                     'url' => $this->appendForAdmin(
                                         $adminUrl,
                                         'product_type'
                                     ),
                                     'text' => 'Product Types',
                                     'css-id' => 'sidebar-link-product-type-list'
                                 ],
                                 [
                                     'id' => 'product-attribute-list',
                                     'url' => $this->appendForAdmin(
                                         $adminUrl,
                                         'product_attribute'
                                     ),
                                     'text' => 'Product Attributes',
                                     'css-id' => 'sidebar-link-product-attribute-list'
                                 ],
                                */
                            ],
                            'roles' => ['ROLE_EMPLOYEE'],
                        ],
                        // todo: implement
                        [
                            'id' => 'customer',
                            'header_text' => 'Customers',
                            'items' => [
                                [
                                    'id' => 'customer-list',
                                    'url' => $this->appendForAdmin(
                                        $adminUrl,
                                        'customer'
                                    ),
                                    'text' => 'Customers',
                                    'css-id' => 'sidebar-link-customer-list'
                                ],
                            ],
                            'roles' => ['ROLE_EMPLOYEE'],
                        ],
                        // todo: implement
                        [
                            'id' => 'location-data',
                            'header_text' => 'Location Data',
                            'items' => [
                                [
                                    'id' => 'country-list',
                                    'url' => $this->appendForAdmin(
                                        $adminUrl,
                                        'country'
                                    ),
                                    'text' => 'Country',
                                    'css-id' => 'sidebar-link-country-list'
                                ],
                                [
                                    'id' => 'state-list',
                                    'url' => $this->appendForAdmin(
                                        $adminUrl,
                                        'customer'
                                    ),
                                    'text' => 'State',
                                    'css-id' => 'sidebar-link-state-list'
                                ],
                                [
                                    'id' => 'city-list',
                                    'url' => $this->appendForAdmin(
                                        $adminUrl,
                                        'city'
                                    ),
                                    'text' => 'City',
                                    'css-id' => 'sidebar-link-city-list'
                                ],
                                [
                                    'id' => 'pin-code-list',
                                    'url' => $this->appendForAdmin(
                                        $adminUrl,
                                        'postal_code'
                                    ),
                                    'text' => 'Pin Code',
                                    'css-id' => 'sidebar-link-postal-list'
                                ],
                            ],
                            'roles' => ['ROLE_EMPLOYEE'],
                        ],
                        // todo: implement
                        [
                            'id' => 'financial-data',
                            'header_text' => 'Financial Data',
                            'items' => [
                                [
                                    'id' => 'currency-list',
                                    'url' => $this->appendForAdmin(
                                        $adminUrl,
                                        'currency'
                                    ),
                                    'text' => 'Currency',
                                    'css-id' => 'sidebar-link-currency-list'
                                ],
                            ],
                            'roles' => ['ROLE_EMPLOYEE'],
                        ],
                        [
                            'id' => 'orders',
                            'header_text' => 'Orders',
                            'items' => [
                                [
                                    'id' => 'order-list',
                                    'url' => $this->appendForAdmin(
                                        $adminUrl,
                                        'order'
                                    ),
                                    'text' => 'Orders',
                                    'css-id' => 'sidebar-link-order-list'
                                ],
                            ],
                            'roles' => ['ROLE_EMPLOYEE'],
                        ],
                        [
                            'id' => 'web-shop',
                            'header_text' => 'WebShop',
                            'items' => [
                                [
                                    'url' => $this->appendForAdmin(
                                        $adminUrl,
                                        'web_shop'
                                    ),
                                    'text' => 'Shops',
                                    'css-id' => 'sidebar-link-web-shop-list'
                                ]
                            ],
                            'roles' => ['ROLE_EMPLOYEE'],
                        ],
                        [
                            'id' => 'users',
                            'header_text' => 'Users',
                            'items' => [
                                [
                                    'id' => 'user-list',
                                    'url' => $this->appendForAdmin(
                                        $adminUrl,
                                        'user'
                                    ),
                                    'text' => 'Users',
                                    'css-id' => 'sidebar-link-user-list'
                                ]
                            ],
                            'roles' => ['ROLE_EMPLOYEE'],
                        ],
                        [
                            'id' => 'files',
                            'header_text' => 'Files',
                            'items' => [
                                [
                                    'url' => $this->appendForAdmin(
                                        $adminUrl,
                                        'file'
                                    ),
                                    'text' => 'files',
                                    'css-id' => 'sidebar-link-file-list'
                                ]
                            ],
                            'roles' => ['ROLE_EMPLOYEE'],
                        ],
                        [
                            'id' => 'settings',
                            'header_text' => 'Settings',
                            'items' => [
                                [
                                    'url' => $this->appendForAdmin(
                                        $adminUrl,
                                        'settings'
                                    ),
                                    'text' => 'Settings',
                                    'css-id' => 'sidebar-link-settings'
                                ]
                            ],
                            'roles' => ['ROLE_EMPLOYEE'],
                        ],
                        [
                            'id' => 'my-orders',
                            'header_text' => 'My Orders',
                            'items' => [
                                [
                                    'id' => 'my-order-list',
                                    'url' => $this->appendForCustomer(
                                        $adminUrl,
                                        'orders'
                                    ),
                                    'text' => 'My Orders',
                                    'css-id' => 'sidebar-link-my-order-list'
                                ]
                            ],
                            'roles' => ['ROLE_CUSTOMER'],
                        ],

                        [
                            'id' => 'my-addresses',
                            'header_text' => 'My Addresses',
                            'items' => [
                                [
                                    'id' => 'my-addresses-list',
                                    'url' => $this->appendForCustomer(
                                        $adminUrl,
                                        'addresses'
                                    ),
                                    'text' => 'My Addresses',
                                    'css-id' => 'sidebar-link-my-addresses-list'
                                ]
                            ],
                            'roles' => ['ROLE_CUSTOMER'],
                        ],
                    ]
            ]

        );
    }

    private function appendForAdmin(string $adminUrl,
        string $function
    ): string {
        return $adminUrl . "?_function={$function}&_type=list";
    }

    private function appendForCustomer(string $adminUrl,
        string $arg
    ): string {
        return $adminUrl . "/{$arg}";
    }
}