<?php

namespace App\Form\MasterData\Product;

use App\Form\CategoryAutoCompleteField;
use App\Form\MasterData\CustomFormType;
use App\Form\MasterData\Product\DTO\ProductDTO;
use App\Form\MasterData\Product\Group\ProductGroupAutoCompleteField;
use App\Repository\CategoryRepository;
use App\Repository\ProductGroupRepository;
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

    public function __construct(
        private readonly CategoryRepository                   $categoryRepository,
        private readonly ProductGroupRepository               $productGroupRepository,
        #[Autowire('%env(APP_ENV)%')] private readonly string $environment)
    {
        parent::__construct($this->environment);
    }

    function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('id', HiddenType::class, ['required' => true]);
        $builder->add('name', TextType::class, ['required' => true]);
        $builder->add('description', TextType::class, ['required' => true]);
        $builder->add('category', CategoryAutoCompleteField::class, ['required' => true, 'mapped' => false]);
        $builder->add('productGroup', ProductGroupAutoCompleteField::class, ['required' => false, 'mapped' => false]);
        $builder->add('isActive', CheckboxType::class);
        $builder->add('save', SubmitType::class);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $formEvent) {
            $data = $formEvent->getData();

            $formEvent->getForm()->add('categoryId', NumberType::class);
            $formEvent->getForm()->add('productGroupId', NumberType::class);

            $data['categoryId'] = $data['category'];
            $data['productGroupId'] = $data['productGroup'];

            $formEvent->setData($data);
        });

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $formEvent) {
            /** @var ProductDTO $data */
            $data = $formEvent->getData();
            $form = $formEvent->getForm();

            $form->get('category')->setData($this->categoryRepository->find($data->categoryId));
            $form->get('productGroup')->setData($this->productGroupRepository->findOneBy(['id' => $data->productGroupId]));
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