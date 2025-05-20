<?php

namespace Silecust\WebShop\Form\Admin\Settings\System;

use Silecust\WebShop\Form\Common\File\DTO\FileDTO;
use Silecust\WebShop\Form\Common\File\FileCreateForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LogoForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('fileDTO', FileCreateForm::class);
    }

    public function getBlockPrefix(): string
    {
        return 'logo_form';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class', FileDTO::class]);
    }
}