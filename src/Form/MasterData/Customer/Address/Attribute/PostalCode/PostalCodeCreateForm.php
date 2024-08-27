<?php

namespace App\Form\MasterData\Customer\Address\Attribute\PostalCode;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PostalCodeCreateForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options):void
    {

        $builder->add('code',TextType::class);
        $builder->add('name',TextType::class);

    }

    public function getBlockPrefix():string
    {
        return 'postal_code_create_form';
    }
}