<?php

namespace Silecust\WebShop\Form\MasterData\Product\Attribute;

use Silecust\WebShop\Form\MasterData\Product\Attribute\DTO\ProductAttributeDTO;
use Silecust\WebShop\Repository\ProductAttributeTypeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductAttributeEditForm extends AbstractType
{
    public function __construct(private ProductAttributeTypeRepository $productAttributeTypeRepository
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('id', TextType::class);
       $builder->add('name', TextType::class);
        $builder->add('description', TextType::class);


        $builder->add('save', SubmitType::class);
    }

    private function fill(): array
    {
        $selectArray = [];
        $productAttributeTypes = $this->productAttributeTypeRepository->findAll();
        foreach ($productAttributeTypes as $bu) {

            $selectArray[$bu->getName()] = $bu->getId();
        }
        return $selectArray;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => ProductAttributeDTO::class]);
    }


    public function getBlockPrefix(): string
    {
        return 'product_attribute_edit_form';
    }
}