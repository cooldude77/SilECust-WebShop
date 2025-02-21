<?php

namespace Silecust\WebShop\Config\Admin;

use Silecust\WebShop\Config\System\FieldListInterface;

class CategoryFieldList implements FieldListInterface
{


    /**
     * @return array
     */
    public function fieldsToShowOnListEntity()
    {
        return ['code', 'description'];
    }
}