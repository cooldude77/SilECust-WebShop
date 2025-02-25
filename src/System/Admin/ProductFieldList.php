<?php

namespace Silecust\WebShop\System\Admin;

use Silecust\WebShop\System\Admin\Interfaces\FieldListInterface;

class ProductFieldList implements FieldListInterface
{


    /**
     * @return array
     */
    public function fieldsToShowOnListEntity()
    {
        return ['code', 'description'];
    }
}