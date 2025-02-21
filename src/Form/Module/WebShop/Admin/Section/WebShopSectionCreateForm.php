<?php

namespace Silecust\WebShop\Form\Module\WebShop\Admin\Section;

use Silecust\WebShop\Form\Module\WebShop\Admin\Section\DTO\WebShopSectionDTO;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WebShopSectionCreateForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class);
        $builder->add('description', TextType::class);
        $builder->add('webShopId',TextType::class);
        $builder->add('sectionOrder',TextType::class);
        $builder->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class'=>WebShopSectionDTO::class]);
    }

}