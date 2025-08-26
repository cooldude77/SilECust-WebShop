<?php

namespace Silecust\WebShop\Service\Admin\SideBar\Action;

use Silecust\WebShop\Exception\Admin\SideBar\Action\EmptyActionListMapException;

class PanelActionListMapBuilder
{

    private PanelActionListMap $actionListMap;

    /**
     * function - product/customer / web shop etc.
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
                            'list' => 'sc_admin_product_list'
                        ]
                    ],
                    'price_product_base' => [
                        'routes' => [
                            'create' => 'sc_admin_price_product_base_create',
                            'edit' => 'sc_admin_price_product_base_edit',
                            'display' => 'sc_admin_price_product_base_display',
                            'list' => 'sc_admin_price_product_base_list'
                        ]
                    ],
                    'price_product_discount' => [
                        'routes' => [
                            'create' => 'sc_admin_price_product_discount_create',
                            'edit' => 'sc_admin_price_product_discount_edit',
                            'display' => 'sc_admin_price_product_discount_display',
                            'list' => 'sc_admin_price_product_discount_list'
                        ]
                    ],
                    'price_product_tax' => [
                        'routes' => [
                            'create' => 'sc_admin_price_product_tax_create',
                            'edit' => 'sc_admin_price_product_tax_edit',
                            'display' => 'sc_admin_price_product_tax_display',
                            'list' => 'sc_admin_price_product_tax_list'
                        ]
                    ],
                    'tax_slab' => [
                        'routes' => [
                            'create' => 'sc_admin_tax_slab_create',
                            'edit' => 'sc_admin_tax_slab_edit',
                            'display' => 'sc_admin_tax_slab_display',
                            'list' => 'sc_admin_tax_slab_list'
                        ]
                    ],
                    'currency' => [
                        'routes' => [
                            'create' => 'sc_admin_currency_create',
                            'edit' => 'sc_admin_currency_edit',
                            'display' => 'sc_admin_currency_display',
                            'list' => 'sc_admin_currency_list'
                        ]
                    ],
                    'product_attribute' => [
                        'routes' => [
                            'create' => 'sc_admin_product_attribute_create',
                            'edit' => 'sc_admin_product_attribute_edit',
                            'display' => 'sc_admin_product_attribute_display',
                            'list' => 'sc_admin_product_attribute_list'
                        ]
                    ],
                    'product_type' => [
                        'routes' => [
                            'create' => 'sc_admin_product_type_create',
                            'edit' => 'sc_admin_product_type_edit',
                            'display' => 'sc_admin_product_type_display',
                            'list' => 'sc_admin_product_type_list'
                        ]
                    ],
                    'customer' => [
                        'routes' => [
                            'create' => 'sc_admin_customer_create',
                            'edit' => 'sc_admin_customer_edit',
                            'display' => 'sc_admin_customer_display',
                            'list' => 'sc_admin_customer_list'
                        ]
                    ],
                    'customer_address' => [
                        'routes' => [
                            'create' => 'sc_admin_customer_address_create',
                            'edit' => 'sc_admin_customer_address_edit',
                            'display' => 'sc_admin_customer_address_display',
                            'list' => 'sc_admin_customer_address_list',
                            'delete' => 'sc_admin_customer_address_delete'
                        ]
                    ],
                    'employee' => [
                        'routes' => [
                            'create' => 'sc_admin_employee_create',
                            'edit' => 'sc_admin_employee_edit',
                            'display' => 'sc_admin_employee_display',
                            'list' => 'sc_admin_employee_list'
                        ]
                    ],
                    'country' => [
                        'routes' => [
                            'create' => 'sc_admin_country_create',
                            'edit' => 'sc_admin_country_edit',
                            'display' => 'sc_admin_country_display',
                            'list' => 'sc_admin_country_list'
                        ]
                    ],
                    'state' => [
                        'routes' => [
                            'create' => 'sc_admin_state_create',
                            'edit' => 'sc_admin_state_edit',
                            'display' => 'sc_admin_state_display',
                            'list' => 'sc_admin_state_list'
                        ]
                    ],
                    'city' => [
                        'routes' => [
                            'create' => 'sc_admin_city_create',
                            'edit' => 'sc_admin_city_edit',
                            'display' => 'sc_admin_city_display',
                            'list' => 'sc_admin_city_list'
                        ]
                    ],
                    'postal_code' => [
                        'routes' => [
                            'create' => 'sc_admin_postal_code_create',
                            'edit' => 'sc_admin_postal_code_edit',
                            'display' => 'sc_admin_postal_code_display',
                            'list' => 'sc_admin_postal_code_list'
                        ]
                    ],
                    'category' => [
                        'routes' => [
                            'create' => 'sc_admin_category_create',
                            'edit' => 'sc_admin_category_edit',
                            'display' => 'sc_admin_category_display',
                            'list' => 'sc_admin_category_list'
                        ]
                    ],
                    'order' => [
                        'routes' => [
                            'create' => 'sc_admin_order_create',
                            'edit' => 'sc_admin_order_edit',
                            'display' => 'sc_admin_order_display',
                            'list' => 'sc_admin_order_list'
                        ]
                    ],
                    'order_item' => [
                        'routes' => [
                            'create' => 'sc_admin_order_item_create',
                            'edit' => 'sc_admin_order_item_edit',
                            'display' => 'sc_admin_order_item_display',
                            'list' => 'sc_admin_order_item_list'
                        ]
                    ],
                    'file' => [
                        'routes' => [
                            'create' => 'sc_admin_file_create',
                            'edit' => 'sc_admin_file_edit',
                            'display' => 'sc_admin_file_display',
                            'list' => 'sc_admin_file_list'
                        ]
                    ],
                    'settings' => [
                        'routes' => [
                            'list' => 'sc_admin_system_settings'
                        ]
                    ],
                    'category_file_image' => [
                        'routes' => [
                            'create' => 'sc_admin_category_file_image_create',
                            'edit' => 'sc_admin_category_file_image_edit',
                            'display' => 'sc_admin_category_file_image_display',
                            'list' => 'sc_admin_category_file_image_list'
                        ]
                    ],
                    'product_file_image' => [
                        'routes' => [
                            'create' => 'sc_admin_product_file_image_create',
                            'edit' => 'sc_admin_product_file_image_edit',
                            'display' => 'sc_admin_product_file_image_display',
                            'list' => 'sc_admin_product_file_image_list'
                        ]
                    ],
                    'web_shop' => [
                        'routes' => [
                            'create' => 'sc_admin_web_shop_create',
                            'edit' => 'sc_admin_web_shop_edit',
                            'display' => 'sc_admin_web_shop_display',
                            'list' => 'sc_admin_web_shop_list'
                        ]
                    ],
                    'sc_my_orders' => [
                        'routes' => [
                            'list' => 'sc_admin_my_order_list',
                            'display' => 'sc_admin_web_shop_display',
                        ]
                    ],
                    'sc_my_addresses' => [
                        'routes' => [
                            'list' => 'sc_admin_my_address_list',
                            'display' => 'sc_admin_my_address_display',
                        ]
                    ],
                ]
            ]
        );
        return $this;
    }

    /**
     * @return PanelActionListMap
     * @throws \Silecust\WebShop\Exception\Admin\SideBar\Action\EmptyActionListMapException
     */
    public function getPanelActionListMap(): PanelActionListMap
    {
        if (empty($this->actionListMap)) {
            throw new EmptyActionListMapException();
        }
        return $this->actionListMap;
    }
}