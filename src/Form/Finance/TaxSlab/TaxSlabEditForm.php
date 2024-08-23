<?php

namespace App\Form\Finance\TaxSlab;

use App\Form\Finance\TaxSlab\DTO\TaxSlabDTO;
use App\Form\MasterData\Currency\CurrencyAutoCompleteField;
use App\Form\MasterData\Price\DTO\PriceProductBaseDTO;
use App\Form\MasterData\Product\ProductAutoCompleteField;
use App\Repository\CountryRepository;
use App\Repository\CurrencyRepository;
use App\Repository\ProductRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;


class TaxSlabEditForm extends AbstractType
{


        public function __construct(private readonly CountryRepository $countryRepository)
    {
    }


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('id', HiddenType::class);
        $builder->add('name', TextType::class);
        $builder->add('description', TextType::class);

        $builder->add('rateOfTax', NumberType::class);
        $builder->add('country', CurrencyAutoCompleteField::class, ['mapped' => false]);
        $builder->add('save', SubmitType::class);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $formEvent) {

            $formEvent->getForm()->add('countryId', HiddenType::class);

            // dto jugglery
            $data = $formEvent->getData();

           $data['countryId'] = $data['country'];

            $formEvent->setData($data);


        });
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $formEvent) {
            /** @var TaxSlabDTO $data */
            $data = $formEvent->getData();
            $form = $formEvent->getForm();

            $form->get('country')->setData($this->countryRepository->findOneBy(['id' => $data->countryId]));
        });


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(['data_class' => TaxSlabDTO::class, 'validation_groups' => ['edit']]);
    }


    public function getBlockPrefix(): string
    {
        return 'taxSlab_edit_form';
    }
}