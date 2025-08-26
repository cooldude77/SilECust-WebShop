<?php

namespace Silecust\WebShop\Service\Testing\Utility;

use JetBrains\PhpStorm\NoReturn;

class DieHere
{
    public static bool $shouldDie = false;

    #[NoReturn] static function die(): void
    {

        \DAMA\DoctrineTestBundle\Doctrine\DBAL\StaticDriver::commit();
        die;
    }
}