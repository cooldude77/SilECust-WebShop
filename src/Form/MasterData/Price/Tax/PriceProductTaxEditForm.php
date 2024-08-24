<?php

namespace App\Form\MasterData\Price\Tax;

use App\Form\Finance\TaxSlab\TaxSlabAutoCompleteField;
use App\Form\MasterData\Price\Tax\DTO\PriceProductTaxDTO;
use App\Form\MasterData\Product\ProductAutoCompleteField;
use App\Repository\ProductRepository;
use App\Repository\TaxSlabRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PriceProductTaxEditForm extends AbstractType
{

    public function __construct(private readonly ProductRepository $productRepository, private readonly TaxSlabRepository $taxSlabRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('id', HiddenType::class);

        $builder->add('product', ProductAutoCompleteField::class, ['mapped' => false]);
        $builder->add('taxSlab', TaxSlabAutoCompleteField::class, ['mapped' => false]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $formEvent) {

            $formEvent->getForm()->add('productId', HiddenType::class);
            $formEvent->getForm()->add('taxSlabId', HiddenType::class);

            // dto jugglery
            $data = $formEvent->getData();

            $data['productId'] = $data['product'];
            $data['taxSlabId'] = $data['taxSlab'];

            $formEvent->setData($data);
        });
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $formEvent) {
            /** @var PriceProductTaxDTO $data */
            $data = $formEvent->getData();
            $form = $formEvent->getForm();

            $form->get('product')->setData($this->productRepository->findOneBy(['id' => $data->productId]));
            $form->get('taxSlab')->setData($this->taxSlabRepository->findOneBy(['id' => $data->taxSlabId]));
        });
        $builder->add('save', SubmitType::class);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => PriceProductTaxDTO::class]);
    }

    public function getBlockPrefix(): string
    {

        return 'price_product_base_edit_form';
    }
}