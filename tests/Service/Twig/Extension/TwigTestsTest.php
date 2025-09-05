<?php

namespace Silecust\WebShop\Tests\Service\Twig\Extension;

use PHPUnit\Framework\TestCase;
use Silecust\WebShop\Service\Twig\Extension\TwigUtility;

class TwigUtilityTest extends TestCase
{


    public function testGetTests()
    {
        $twig = new TwigUtility();
        self::assertTrue($twig->isInstanceOf(['1'], 'array'));
        self::assertFalse($twig->isInstanceOf('1', 'array'));

    }
}
