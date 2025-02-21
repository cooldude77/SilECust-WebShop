<?php

namespace Silecust\WebShop\Controller\Common\Utility;

class CommonUtility
{

    public function addIdToUrl(string $url , int $id):string
    {

         return "{$url}&id={$id}";

    }
}