<?php

namespace Silecust\WebShop\Form\MasterData\Customer\Address;

use Silecust\WebShop\Form\MasterData\Customer\Address\Attribute\PostalCode\PostalCodeAutoCompleteField;
use Silecust\WebShop\Form\MasterData\Customer\Address\DTO\CustomerAddressDTO;
use Silecust\WebShop\Repository\PostalCodeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerAddressCreateForm extends AbstractType
{

    public function __construct(private readonly PostalCodeRepository $postalCodeRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('customerId', HiddenType::class);
        $builder->add('line1', TextType::class);
        $builder->add('line2', TextType::class);
        $builder->add('line3', TextType::class);
        $builder->add('postalCode', PostalCodeAutoCompleteField::class, ['mapped' => false]);
        $builder->add(
            'addressType', ChoiceType::class,
            [
                'choices' => [
                    'Shipping' => 'shipping',
                    'Billing' => 'billing',
                ],
                'multiple' => false,
                'expanded' => true,
            ]
        );
        $builder->add('isDefault', CheckboxType::class, ['label' => 'Use as default address']);
        $builder->add('save', SubmitType::class);


        $builder->addEventListener(
            FormEvents::PRE_SUBMIT, function (FormEvent $formEvent) {
            $formEvent->getForm()->add('postalCodeId', HiddenType::class);

            $data = $formEvent->getData();
            $data['postalCodeId'] = $data['postalCode'];

            $formEvent->setData($data);
        }
        );

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA, function (FormEvent $formEvent) use ($options) {

            /** @var CustomerAddressDTO $data */
            $data = $formEvent->getData();
            $form = $formEvent->getForm();

            $data->addressType = $options['addressType'];


            $form->get('postalCode')->setData($this->postalCodeRepository->findOneBy(['id' => $data->postalCodeId]));

        }
        );

    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => CustomerAddressDTO::class]);

        $resolver->setRequired(['addressType']);

    }

    public function getBlockPrefix(): string
    {
        return 'customer_address_create_form';
    }

}