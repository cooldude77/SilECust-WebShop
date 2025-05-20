<?php

namespace Silecust\WebShop\Form\Module\WebShop\External\Address\Existing;

use Silecust\WebShop\Form\Module\WebShop\External\Address\Existing\DTO\AddressChooseExistingSingleDTO;

use Silecust\WebShop\Service\MasterData\Customer\Address\CustomerAddressQuery;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressChooseExistingSingleForm extends AbstractType
{

    //todo : How to create a radio button
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('isChosen', CheckboxType::class,['label' => false,'required'=>false]);
        $builder->add('id', HiddenType::class);

    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', AddressChooseExistingSingleDTO::class);

    }

    public function getBlockPrefix(): string
    {
        return 'address_choose_existing_single_form';
    }
}