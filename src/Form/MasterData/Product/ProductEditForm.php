<?php

namespace Silecust\WebShop\Form\MasterData\Product;

use Silecust\WebShop\Form\CategoryAutoCompleteField;
use Silecust\WebShop\Form\MasterData\CustomFormType;
use Silecust\WebShop\Form\MasterData\Product\DTO\ProductDTO;
use Silecust\WebShop\Repository\ProductRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductEditForm extends CustomFormType
{

    public function __construct(private readonly ProductRepository                    $productRepository,
                                #[Autowire('%env(APP_ENV)%')] private readonly string $environment)
    {
        parent::__construct($this->environment);
    }

    function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('id', HiddenType::class);
        $builder->add('name', TextType::class);
        $builder->add('description', TextType::class);
        $builder->add('category', CategoryAutoCompleteField::class, ['mapped' => false]);
        $builder->add('isActive', CheckboxType::class, ['required' => false]);
        $builder->add('save', SubmitType::class);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $formEvent) {
            $data = $formEvent->getData();
            $formEvent->getForm()->add('categoryId', NumberType::class);
            $data['categoryId'] = $data['category'];
            $formEvent->setData($data);
        });

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $formEvent) {
            /** @var ProductDTO $data */
            $data = $formEvent->getData();
            $form = $formEvent->getForm();

            $form->get('category')->setData($this->productRepository->findOneBy(['id' => $data->categoryId]));
        });


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(['data_class' => ProductDTO::class]);
    }

    public function getBlockPrefix(): string
    {
        return 'product_edit_form';
    }
}