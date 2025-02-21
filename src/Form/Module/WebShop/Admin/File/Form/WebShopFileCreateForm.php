<?php

namespace Silecust\WebShop\Form\Module\WebShop\Admin\File\Form;

use Silecust\WebShop\Form\Module\WebShop\Admin\File\DTO\WebShopFileDTO;
use Silecust\WebShop\Form\Common\File\DTO\FileDTO;
use Silecust\WebShop\Form\Common\File\FileCreateForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WebShopFileCreateForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('webShopId', TextType::class);
        $builder->add('fileFormDTO',FileCreateForm::class);

    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class'=>WebShopFileDTO::class]);
    }
}