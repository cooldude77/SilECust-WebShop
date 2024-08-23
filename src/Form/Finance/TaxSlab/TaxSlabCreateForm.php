<?php

namespace App\Form\Finance\TaxSlab;

use App\Form\Finance\TaxSlab\DTO\TaxSlabDTO;
use App\Form\MasterData\Currency\CurrencyAutoCompleteField;
use App\Form\MasterData\CustomFormType;
use App\Form\MasterData\Product\ProductAutoCompleteField;
use App\Form\TaxSlabAutoCompleteField;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaxSlabCreateForm extends CustomFormType
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
        $builder->add('country', ProductAutoCompleteField::class, ['mapped' => false]);
        $builder->add('rateOfTax', NumberType::class);
        $builder->add('save', SubmitType::class);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $formEvent) {

            $formEvent->getForm()->add('countryId', HiddenType::class);
            $formEvent->getForm()->add('currencyId', HiddenType::class);

            // dto jugglery
            $data = $formEvent->getData();

            $data['countryId'] = $data['country'];

            $formEvent->setData($data);

        });


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('data_class', TaxSlabDTO::class);
    }


    public function getBlockPrefix(): string
    {
        return 'taxSlab_create_form';
    }
}