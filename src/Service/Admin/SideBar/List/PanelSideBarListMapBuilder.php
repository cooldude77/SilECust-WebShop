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
                                // todo: implement
                                [
                                    'id' => 'product-group-list',
                                    'url' => $this->appendForAdmin(
                                        $adminUrl,
                                        'product_group'
                                    ),
                                    'text' => 'Product Group',
                                    'css-id' => 'sidebar-link-product-group-list'
                                ],
                                [
                                    'id' => 'product-attribute-key-list',
                                    'url' => $this->appendForAdmin(
                                        $adminUrl,
                                        'product_attribute_key'
                                    ),
                                    'text' => 'Product Attribute Key',
                                    'css-id' => 'sidebar-link-product-attribute-key-list'
                                ],

                            ],
                            'roles' => ['ROLE_EMPLOYEE'],
                        ],
                        [
                            'id' => 'price',
                            'header_text' => 'Prices',
                            'items' => [
                                [
                                    'id' => 'price-product-base-list',
                                    'url' => $this->appendForAdmin(
                                        $adminUrl,
                                        'price_product_base'
                                    ),
                                    'text' => 'Base price',
                                    'css-id' => 'sidebar-link-price-product-base-list'
                                ],
                                [
                                    'id' => 'price-product-discount-list',
                                    'url' => $this->appendForAdmin(
                                        $adminUrl,
                                        'price_product_discount'
                                    ),
                                    'text' => 'Discount',
                                    'css-id' => 'sidebar-link-price-discount-list'
                                ],
                                [
                                    'id' => 'price-product-tax-list',
                                    'url' => $this->appendForAdmin(
                                        $adminUrl,
                                        'price_product_tax'
                                    ),
                                    'text' => 'Tax',
                                    'css-id' => 'sidebar-link-price-tax-list'
                                ],
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
                                    'text' => 'Country/State(etc.)',
                                    'css-id' => 'sidebar-link-country-list'
                                ],
                            ],
                            'roles' => ['ROLE_EMPLOYEE'],
                        ],
                        // todo: implement
                        [
                            'id' => 'finance-data',
                            'header_text' => 'Finance Data',
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
                                [
                                    'id' => 'tax-slabs-list',
                                    'url' => $this->appendForAdmin(
                                        $adminUrl,
                                        'tax_slab'
                                    ),
                                    'text' => 'Tax Slabs',
                                    'css-id' => 'sidebar-link-tax-slabs-list'
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
                        /* [
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
                        */
                        [
                            'id' => 'employee',
                            'header_text' => 'Employees',
                            'items' => [
                                [
                                    'id' => 'employee-list',
                                    'url' => $this->appendForAdmin(
                                        $adminUrl,
                                        'employee'
                                    ),
                                    'text' => 'Employees',
                                    'css-id' => 'sidebar-link-employee-list'
                                ],
                            ],
                            'roles' => ['ROLE_SUPER_ADMIN'],
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
                        [
                            'id' => 'my-personal-info',
                            'header_text' => 'My Personal Information',
                            'items' => [
                                [
                                    'id' => 'my-personal-info',
                                    'url' => $this->appendForCustomer(
                                        $adminUrl,
                                        'personal-info'
                                    ),
                                    'text' => 'My Personal Info',
                                    'css-id' => 'sidebar-link-my-personal-info'
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
    ): string
    {
        return $adminUrl . "?_function=$function&_type=list";
    }

    private function appendForCustomer(string $adminUrl,
                                       string $arg
    ): string
    {
        return $adminUrl . "/$arg";
    }
}