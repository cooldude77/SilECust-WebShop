<?php

namespace App\Service\Component;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;


class JsonDecode extends AbstractExtension
{
    public function getName()
    {
        return 'twig.json_decode';
    }

    public function getFilters():array
    {
        return array(
              new TwigFilter('json_decode'  , array($this,'jsonDecode'))
        );
    }

    public function jsonDecode($string)
    {
        return json_decode($string);
    }
}