<?php

namespace App\Form\MasterData\Product\Group;

use App\Form\MasterData\Product\Group\DTO\ProductGroupDTO;
use Symfony\Component\Form\AbstractGroup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Group\SubmitGroup;
use Symfony\Component\Form\Extension\Core\Group\TextGroup;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductGroupCreateForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class);
        $builder->add('description', TextType::class);
        $builder->add('Save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => ProductGroupDTO::class]);
    }

    public function getBlockPrefix(): string
    {
        return 'product_group_create_form';
    }
}