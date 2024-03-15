<?php

namespace App\Form\Admin\Product\Image;

use App\Form\Admin\Product\Category\Transformer\CategoryToIdTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductFileCreateForm extends AbstractType
{
    private CategoryToIdTransformer $categoryToIdTransformer;

    public function __construct(
        CategoryToIdTransformer $categoryToIdTransformer)
    {

        $this->categoryToIdTransformer = $categoryToIdTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('code', TextType::class);
        $builder->add('description', TextType::class);
        $builder->add('longDescription', TextareaType::class);
        $builder->add('category', TextType::class);
        $builder->add('isActive', CheckboxType::class);

        $builder->get('category')->addModelTransformer($this->categoryToIdTransformer);

        $builder->add('save', SubmitType::class);

    }
}