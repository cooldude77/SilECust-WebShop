<?php

namespace App\Form\MasterData;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomFormType extends AbstractType
{


    public function __construct(private readonly ?string $environment = null)
    {
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        if ($this->environment == 'test')
            $resolver->setDefault('csrf_protection', false);
    }

}