<?php

namespace Silecust\WebShop\Form\MasterData\Price;

use Silecust\WebShop\Form\Finance\Currency\CurrencyAutoCompleteField;
use Silecust\WebShop\Form\MasterData\Price\Product\DTO\PriceProductBaseDTO;
use Silecust\WebShop\Form\MasterData\Product\ProductAutoCompleteField;
use Silecust\WebShop\Repository\CurrencyRepository;
use Silecust\WebShop\Repository\ProductRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PriceProductBaseEditForm extends AbstractType
{

    public function __construct(private readonly ProductRepository $productRepository, private readonly CurrencyRepository $currencyRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('id', HiddenType::class);

        $builder->add('product', ProductAutoCompleteField::class, ['mapped' => false]);
        $builder->add('price', NumberType::class);
        $builder->add('currency', CurrencyAutoCompleteField::class, ['mapped' => false]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $formEvent) {

            $formEvent->getForm()->add('productId', HiddenType::class);
            $formEvent->getForm()->add('currencyId', HiddenType::class);

            // dto jugglery
            $data = $formEvent->getData();

            $data['productId'] = $data['product'];
            $data['currencyId'] = $data['currency'];

            $formEvent->setData($data);
        });
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $formEvent) {
            /** @var PriceProductBaseDTO $data */
            $data = $formEvent->getData();
            $form = $formEvent->getForm();

            $form->get('product')->setData($this->productRepository->findOneBy(['id' => $data->productId]));
            $form->get('currency')->setData($this->currencyRepository->findOneBy(['id' => $data->currencyId]));
        });
        $builder->add('save', SubmitType::class);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => PriceProductBaseDTO::class]);
    }

    public function getBlockPrefix(): string
    {

        return 'price_product_base_edit_form';
    }
}