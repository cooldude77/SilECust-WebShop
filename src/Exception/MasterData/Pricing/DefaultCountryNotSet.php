<?php

namespace App\Exception\MasterData\Pricing;

class DefaultCountryNotSet extends  \Exception
{

    public function __construct()
    {
        parent::__construct("The parameter default country is not set up in .env file.\n
         Please add SILECUST_DEFAULT_COUNTRY in files for that environment");
    }
}