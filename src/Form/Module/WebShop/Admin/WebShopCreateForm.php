<?php

namespace App\Form\Module\WebShop\Admin;

use App\Form\Module\WebShop\Admin\DTO\WebShopDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WebShopCreateForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class);
        $builder->add('description', TextType::class);
        $builder->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
     $resolver->setDefaults(['data_class'=>WebShopDTO::class]);
    }
}