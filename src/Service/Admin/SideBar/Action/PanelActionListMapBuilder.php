<?php

namespace App\Service\Admin\SideBar\Action;

use App\Exception\Admin\SideBar\Action\EmptyActionListMapException;

class PanelActionListMapBuilder
{

    private PanelActionListMap $actionListMap;

    /**
     * function - product/customer / webshop etc
     * route -> route names related to processes of a function
     */
    public function build(): PanelActionListMapBuilder
    {
        $this->actionListMap = new PanelActionListMap(
            [
                'functions' => [
                    'product' => [
                        'routes' => [
                            'create' => 'sc_admin_product_create',
                            'edit' => 'sc_admin_product_edit',
                            'display' => 'sc_admin_product_display',
                            'list' => 'product_list'
                        ]
                    ],
                    'price_product_base' => [
                        'routes' => [
                            'create' => 'price_product_base_create',
                            'edit' => 'price_product_base_edit',
                            'display' => 'price_product_base_display',
                            'list' => 'price_product_base_list'
                        ]
                    ],
                    'price_product_discount' => [
                        'routes' => [
                            'create' => 'price_product_discount_create',
                            'edit' => 'price_product_discount_edit',
                            'display' => 'price_product_discount_display',
                            'list' => 'price_product_discount_list'
                        ]
                    ],
                    'price_product_tax' => [
                        'routes' => [
                            'create' => 'price_product_tax_create',
                            'edit' => 'price_product_tax_edit',
                            'display' => 'price_product_tax_display',
                            'list' => 'price_product_tax_list'
                        ]
                    ],
                    'tax_slab' => [
                        'routes' => [
                            'create' => 'tax_slab_create',
                            'edit' => 'tax_slab_edit',
                            'display' => 'tax_slab_display',
                            'list' => 'tax_slab_list'
                        ]
                    ],
                    'product_attribute' => [
                        'routes' => [
                            'create' => 'product_attribute_create',
                            'edit' => 'product_attribute_edit',
                            'display' => 'product_attribute_display',
                            'list' => 'product_attribute_list'
                        ]
                    ],
                    'product_type' => [
                        'routes' => [
                            'create' => 'product_type_create',
                            'edit' => 'product_type_edit',
                            'display' => 'product_type_display',
                            'list' => 'product_type_list'
                        ]
                    ],
                    'customer' => [
                        'routes' => [
                            'create' => 'customer_create',
                            'edit' => 'customer_edit',
                            'display' => 'customer_display',
                            'list' => 'customer_list'
                        ]
                    ],
                    'customer_address' => [
                        'routes' => [
                            'create' => 'customer_address_create',
                            'edit' => 'customer_address_edit',
                            'display' => 'customer_address_display',
                            'list' => 'customer_address_list'
                        ]
                    ],
                    'employee' => [
                        'routes' => [
                            'create' => 'employee_create',
                            'edit' => 'employee_edit',
                            'display' => 'employee_display',
                            'list' => 'employee_list'
                        ]
                    ],
                    'country' => [
                        'routes' => [
                            'create' => 'country_create',
                            'edit' => 'country_edit',
                            'display' => 'country_display',
                            'list' => 'country_list'
                        ]
                    ],
                    'state' => [
                        'routes' => [
                            'create' => 'state_create',
                            'edit' => 'state_edit',
                            'display' => 'state_display',
                            'list' => 'state_list'
                        ]
                    ],
                    'city' => [
                        'routes' => [
                            'create' => 'city_create',
                            'edit' => 'city_edit',
                            'display' => 'city_display',
                            'list' => 'city_list'
                        ]
                    ],
                    'postal_code' => [
                        'routes' => [
                            'create' => 'postal_code_create',
                            'edit' => 'postal_code_edit',
                            'display' => 'postal_code_display',
                            'list' => 'postal_code_list'
                        ]
                    ],
                    'category' => [
                        'routes' => [
                            'create' => 'sc_route_admin_category_create',
                            'edit' => 'sc_route_admin_category_edit',
                            'display' => 'sc_route_admin_category_display',
                            'list' => 'sc_route_admin_category_list'
                        ]
                    ],
                    'order' => [
                        'routes' => [
                            'create' => 'order_create',
                            'edit' => 'order_edit',
                            'display' => 'order_display',
                            'list' => 'order_list'
                        ]
                    ],
                    'order_item' => [
                        'routes' => [
                            'create' => 'order_item_create',
                            'edit' => 'order_item_edit',
                            'display' => 'order_item_display',
                            'list' => 'order_item_list'
                        ]
                    ],
                    'file' => [
                        'routes' => [
                            'create' => 'file_create',
                            'edit' => 'file_edit',
                            'display' => 'file_display',
                            'list' => 'file_list'
                        ]
                    ],
                    'settings' => [
                        'routes' => [
                            'list' => 'system_settings'
                        ]
                    ],
                    'category_file_image' => [
                        'routes' => [
                            'create' => 'sc_route_admin_category_file_image_create',
                            'edit' => 'sc_route_admin_category_file_image_edit',
                            'display' => 'sc_route_admin_category_file_image_display',
                            'list' => 'sc_route_admin_category_file_image_list'
                        ]
                    ],
                    'product_file_image' => [
                        'routes' => [
                            'create' => 'product_file_image_create',
                            'edit' => 'product_file_image_edit',
                            'display' => 'product_file_image_display',
                            'list' => 'product_file_image_list'
                        ]
                    ],
                    'web_shop' => [
                        'routes' => [
                            'create' => 'web_shop_create',
                            'edit' => 'web_shop_edit',
                            'display' => 'web_shop_display',
                            'list' => 'web_shop_list'
                        ]
                    ],
                    'my_orders' => [
                        'routes' => [
                            'list' => 'my_order_list',
                            'display' => 'web_shop_display',
                        ]
                    ],
                    'my_addresses' => [
                        'routes' => [
                            'list' => 'my_address_list',
                            'display' => 'my_address_display',
                        ]
                    ],
                ]
            ]
        );
        return $this;
    }

    /**
     * @return PanelActionListMap
     */
    public function getPanelActionListMap(): PanelActionListMap
    {
        if (empty($this->actionListMap)) {
            throw new EmptyActionListMapException();
        }
        return $this->actionListMap;
    }
}