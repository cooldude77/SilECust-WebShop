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
                                    'url' => $this->append(
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
                                    'url' => $this->append(
                                        $adminUrl,
                                        'product'
                                    ),
                                    'text' => 'Products',
                                    'css-id' => 'sidebar-link-product-list'
                                ],
                                [
                                    'id' => 'product-type-list',
                                    'url' => $this->append(
                                        $adminUrl,
                                        'product_type'
                                    ),
                                    'text' => 'Product Types',
                                    'css-id' => 'sidebar-link-product-type-list'
                                ],
                                [
                                    'id' => 'product-attribute-list',
                                    'url' => $this->append(
                                        $adminUrl,
                                        'product_attribute'
                                    ),
                                    'text' => 'Product Attributes',
                                    'css-id' => 'sidebar-link-product-attribute-list'
                                ],
                            ],
                            'roles' => ['ROLE_EMPLOYEE'],
                        ],
                        [
                            'id' => 'customers',
                            'header_text' => 'Customers',
                            'items' => [
                                [
                                    'id' => 'customer-list',
                                    'url' => $this->append(
                                        $adminUrl,
                                        'customer'
                                    ),
                                    'text' => 'Customer',
                                    'css-id' => 'sidebar-link-customer-list'
                                ],
                                [
                                    'id' => 'customer-address-list',
                                    'url' => $this->append(
                                        $adminUrl,
                                        'customer_address'
                                    ),
                                    'text' => 'Customer Address',
                                    'css-id' => 'sidebar-link-customer-address-list'
                                ],
                                [
                                    'id' => 'country-list',
                                    'url' => $this->append(
                                        $adminUrl,
                                        'country'
                                    ),
                                    'text' => 'Country',
                                    'css-id' => 'sidebar-link-country-list'
                                ],
                                [
                                    'id' => 'state-list',
                                    'url' => $this->append(
                                        $adminUrl,
                                        'customer'
                                    ),
                                    'text' => 'State',
                                    'css-id' => 'sidebar-link-state-list'
                                ],
                                [
                                    'id' => 'city-list',
                                    'url' => $this->append(
                                        $adminUrl,
                                        'city'
                                    ),
                                    'text' => 'City',
                                    'css-id' => 'sidebar-link-city-list'
                                ],
                                [
                                    'id' => 'pin-code-list',
                                    'url' => $this->append(
                                        $adminUrl,
                                        'postal_code'
                                    ),
                                    'text' => 'Pin Code',
                                    'css-id' => 'sidebar-link-postal-list'
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
                                    'url' => $this->append(
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
                                    'url' => $this->append(
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
                                    'url' => $this->append(
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
                                    'url' => $this->append(
                                        $adminUrl,
                                        'file'
                                    ),
                                    'text' => 'files',
                                    'css-id' => 'sidebar-link-file-list'
                                ]
                            ],
                            'roles' => ['ROLE_EMPLOYEE'],
                        ],                   [
                            'id' => 'settings',
                            'header_text' => 'Settings',
                            'items' => [
                                [
                                    'url' => $this->append(
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
                                    'url' => $this->append(
                                        $adminUrl,
                                        'my_orders'
                                    ),
                                    'text' => 'My Orders',
                                    'css-id' => 'sidebar-link-my-order-list'
                                ]
                            ],
                            'roles' => ['ROLE_CUSTOMER'],
                        ],
                        [
                            'id' => 'my-personal-details',
                            'header_text' => 'My Personal Details',
                            'items' => [
                                [
                                    'id' => 'my-personal-details',
                                    'url' => $this->append(
                                        $adminUrl,
                                        'my_personal_details'
                                    ),
                                    'text' => 'My Personal Details',
                                    'css-id' => 'sidebar-link-my-personal-details-list'
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
                                    'url' => $this->append(
                                        $adminUrl,
                                        'my_addresses'
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

    private function append(string $adminUrl,
        string $function
    ): string {
        return $adminUrl . "?_function={$function}&_type=list";
    }
}