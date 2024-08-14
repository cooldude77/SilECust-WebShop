<?php

namespace App\Form\Module\WebShop\External\CheckOut\Address;

use App\Form\Module\WebShop\External\CheckOut\Address\DTO\AddressDTO;
use App\Service\MasterData\Customer\Address\CustomerAddressSave;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressSingle extends AbstractType
{

    public function __construct(private readonly CustomerAddressSave $customerAddressService)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder->add('id', HiddenType::class);
        $builder->add('addressChoice', ChoiceType::class, ['multiple' => false,
                                                           'expanded' => true,
                                                           'mapped' => false]);
        $builder->add('addressInALine', TextType::class, ['mapped' => false]);

        $builder->get('addressInALine')->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $formEvent) {

                $id = $formEvent->getData();
                //    $formEvent->setData($this->customerAddressService->getAddressInASingleLine($id));
                $X = 0;
            }
        );

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $formEvent) {

            /** @var AddressDTO $dto */
            $dto = $formEvent->getData();
            $line = $this->customerAddressService->getAddressInASingleLine($dto->id);

            $formEvent->getForm()->get('addressInALine')->setData($line);

        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => AddressDTO::class]);

    }

    private function getAddress()
    {


    }
}