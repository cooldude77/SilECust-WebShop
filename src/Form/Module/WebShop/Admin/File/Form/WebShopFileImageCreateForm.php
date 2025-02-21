<?php

namespace Silecust\WebShop\Form\Module\WebShop\Admin\File\Form;

use Silecust\WebShop\Entity\WebShopImageType;
use Silecust\WebShop\Form\Module\WebShop\Admin\File\DTO\WebShopFileDTO;
use Silecust\WebShop\Form\Module\WebShop\Admin\File\DTO\WebShopFileImageDTO;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WebShopFileImageCreateForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('webShopFileDTO', WebShopFileCreateForm::class);

        $builder->add('imageType', EntityType::class, ['class' => WebShopImageType::class, 'choice_label' => 'description', 'choice_value' => 'id']);

        $builder->add('save', SubmitType::class, array('label' => 'Submit'));

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class'=>WebShopFileImageDTO::class]);
    }
}