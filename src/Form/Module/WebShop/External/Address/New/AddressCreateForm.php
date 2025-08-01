<?php

namespace Silecust\WebShop\Form\Module\WebShop\External\Address\New;

use Silecust\WebShop\Form\MasterData\Customer\Address\CustomerAddressCreateForm;
use Silecust\WebShop\Form\Module\WebShop\External\Address\New\DTO\AddressCreateAndChooseDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Address create form from checkout address screen
 * Can choose , can mark the address as default
 */
class AddressCreateForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // use address structure
        // default is in this structure
        $builder->add(
            'address', CustomerAddressCreateForm::class);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();
            $options = $event->getForm()->getConfig()->getOptions();

            $form->get('address')->remove('save');
            $event->getForm()->add('save', SubmitType::class);


        });


    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            ['data_class' => AddressCreateAndChooseDTO::class]
        );
        $resolver->setRequired(['addressType']);

    }

    public function getBlockPrefix(): string
    {
        return 'address_create_and_choose_form';
    }
}