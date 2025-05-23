<?php

namespace Silecust\WebShop\Form\MasterData\Category;

use Silecust\WebShop\Form\CategoryAutoCompleteField;
use Silecust\WebShop\Form\MasterData\Category\DTO\CategoryDTO;
use Silecust\WebShop\Form\MasterData\CustomFormType;
use Silecust\WebShop\Repository\CategoryRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryCreateForm extends CustomFormType
{
    public function __construct(
        #[Autowire('%env(APP_ENV)%')] private readonly string $environment)
    {
        parent::__construct($this->environment);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class);
        $builder->add('description', TextType::class);
        $builder->add('parentId', CategoryAutoCompleteField::class, ['required' => false, 'mapped' => false]);
        $builder->add('save', SubmitType::class);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $formEvent) {
            $data = $formEvent->getData();
            $formEvent->getForm()->add('parent', NumberType::class);
            $data['parent'] = $data['parentId'];
            $formEvent->setData($data);
        });

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('data_class', CategoryDTO::class);
    }


    public function getBlockPrefix(): string
    {
        return 'category_create_form';
    }
}