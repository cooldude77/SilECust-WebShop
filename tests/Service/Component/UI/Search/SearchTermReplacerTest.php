<?php

namespace Silecust\WebShop\Tests\Service\Component\UI\Search;

use PHPUnit\Framework\TestCase;
use Silecust\WebShop\Service\Component\UI\Search\SearchTermReplacer;

class SearchTermReplacerTest extends TestCase
{

    public function testCheckAndReplaceSearchTerm()
    {
        $replacer = new SearchTermReplacer();

        $testString = "https://test.com?u=x&searchTerm=abcd";

        self::assertEquals("https://test.com?u=x&searchTerm=pqrs",
            $replacer->checkAndReplaceSearchTerm($testString, 'pqrs'));


    }
}
