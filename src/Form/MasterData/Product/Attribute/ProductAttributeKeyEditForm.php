<?php

namespace App\Form\MasterData\Product\Attribute;

use App\Form\MasterData\Product\Attribute\DTO\ProductAttributeKeyDTO;
use App\Repository\ProductAttributeKeyTypeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductAttributeKeyEditForm extends AbstractType
{
    public function __construct(private ProductAttributeKeyTypeRepository $ProductAttributeKeyTypeRepository
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('id', HiddenType::class);
        $builder->add('name', TextType::class);
        $builder->add('description', TextType::class);


        $builder->add('save', SubmitType::class);
    }

    private function fill(): array
    {
        $selectArray = [];
        $ProductAttributeKeyTypes = $this->ProductAttributeKeyTypeRepository->findAll();
        foreach ($ProductAttributeKeyTypes as $bu) {

            $selectArray[$bu->getType()] = $bu->getId();
        }
        return $selectArray;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => ProductAttributeKeyDTO::class]);
    }


    public function getBlockPrefix(): string
    {
        return 'product_attribute_edit_form';
    }
}