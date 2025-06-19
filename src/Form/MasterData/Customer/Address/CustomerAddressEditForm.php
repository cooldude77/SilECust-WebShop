<?php /** @noinspection ALL */

namespace Silecust\WebShop\Form\MasterData\Customer\Address;

use Silecust\WebShop\Form\MasterData\Customer\Address\Attribute\PostalCode\PostalCodeAutoCompleteField;
use Silecust\WebShop\Form\MasterData\Customer\Address\DTO\CustomerAddressDTO;
use Silecust\WebShop\Repository\PostalCodeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerAddressEditForm extends AbstractType
{


    public function __construct(private readonly PostalCodeRepository $postalCodeRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('id', HiddenType::class);
        $builder->add('line1', TextType::class);
        $builder->add('line2', TextType::class, ['required' => false]);
        $builder->add('line3', TextType::class, ['required' => false]);

        $builder->add('postalCode', PostalCodeAutoCompleteField::class, ['mapped' => false, 'required' => false, 'label' => 'Search Postal Codes']);
        $builder->add('currentPostalCodeText', TextType::class, ['disabled' => true, 'label' => 'Postal Code ']);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $formEvent) {
            /** @var CustomerAddressDTO $data */
            $data = $formEvent->getData();
            $form = $formEvent->getForm();

            $id = $data->postalCodeId;
            $postalCode = $this->postalCodeRepository->find($id);
            $data->currentPostalCodeText = $postalCode->getCode();

            if (in_array('shipping', $data->addressTypes))
                $form->add(
                    'addressTypeDefaults', ChoiceType::class,
                    [
                        'choices' => [
                            'Use as default shipping' => 'useAsDefaultShipping',
                        ],
                        'multiple' => true,
                        'expanded' => true,
                    ]
                );
            else if (in_array('billing', $data->addressTypes))
                $form->add(
                    'addressTypeDefaults', ChoiceType::class,
                    [
                        'choices' => [
                            'Use as default billing' => 'useAsDefaultBilling',
                        ],
                        'multiple' => true,
                        'expanded' => true,
                    ]
                );

            $form->add('save', SubmitType::class);

            $formEvent->setData($data);

        });


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => CustomerAddressDTO::class]);
    }

    public function getBlockPrefix(): string
    {
        return 'customer_address_edit_form';
    }

}