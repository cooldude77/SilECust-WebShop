<?php

namespace Silecust\WebShop\Exception\Admin\Common;

class FunctionNotMappedToAnyEntity extends \Exception
{


    /**
     * @param string $function
     */
    public function __construct(string $function)
    {
        parent::__construct(
            "{$function} not mapped to a category in Function Category Mapper For Admin "
        );
    }
}