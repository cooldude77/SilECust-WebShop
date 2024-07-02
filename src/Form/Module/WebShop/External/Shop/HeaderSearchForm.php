<?php

namespace App\Form\Module\WebShop\External\Shop;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class HeaderSearchForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder->add('searchTerm', TextType::class, ['required' => false]);
        $builder->add('Search', SubmitType::class);


    }

    public function getBlockPrefix(): string
    {
        return 'shop_header_search_form';
    }

}