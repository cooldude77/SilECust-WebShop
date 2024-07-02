<?php

namespace App\Service\Admin\Action;

use App\Service\Admin\Action\Exception\EmptyActionListMapException;

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
                            'create' => 'product_create',
                            'edit' => 'product_edit',
                            'display' => 'product_display',
                            'list' => 'product_list'
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
                            'create' => 'category_create',
                            'edit' => 'category_edit',
                            'display' => 'category_display',
                            'list' => 'category_list'
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
                            'create' => 'category_file_image_create',
                            'edit' => 'category_file_image_edit',
                            'display' => 'category_file_image_display',
                            'list' => 'category_file_image_list'
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