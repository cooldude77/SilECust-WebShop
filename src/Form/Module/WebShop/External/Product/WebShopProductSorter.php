<?php

namespace App\Form\Module\WebShop\External\Product;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WebShopProductSorter extends AbstractType
{


    // Important: Making the form action as GET does not make much of a difference as any existing parameter
    // in url is not added to get parameters from this form
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder->add('sort_by', ChoiceType::class, [
            'choices' => [
                'Default' => 'default',
                'Price' => 'price'
            ],
        ]);
        $builder->add('order', ChoiceType::class, [
            'choices' => [
                'Higher First' => 'desc',
                'Lower First' => 'asc'
            ],
        ]);
        $builder->add('submit', SubmitType::class,['label'=>'Sort']);
    }


}