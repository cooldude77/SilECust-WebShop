<?php

namespace App\Tests\Utility;

use Zenstruck\Browser;

trait SelectElement
{
    /**
     * @param Browser $browser
     * @param string  $filter
     * @param int     $chosenValue
     *
     * @return void
     *
     *
     *
     *  All these will work
     *  A select with the name
     *  $selectElement = $browser->crawler()->filter('select[id="price_product_base_create_form_productId"]');
     *  $selectElement = $browser->crawler()->filter('select[name="price_product_base_create_form[productId]"]');
     * $selectElement = $browser->crawler()->filter('[name=price_product_base_create_form\\[productId\\]]');
     */

    public function addOption(Browser $browser, string $filter, int $chosenValue): void
    {
        $domDocument = $browser->crawler()->getNode(0)?->parentNode;

        $option = $domDocument->createElement('option');
        $option->setAttribute('value', $chosenValue);

        $selectElement = $browser->crawler()->filter($filter)->getNode(0);
        $selectElement->appendChild($option);

    }
}