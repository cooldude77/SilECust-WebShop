<?php

namespace App\Form\MasterData\Customer\Address\Attribute\PostalCode;

use App\Form\MasterData\Customer\Address\Attribute\City\CityAutoCompleteField;
use App\Form\MasterData\Customer\Address\Attribute\State\StateAutoCompleteField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PostalCodeEditForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder->add('id', HiddenType::class);
        $builder->add('postalCode', TextType::class);
        $builder->add('cityId', CityAutoCompleteField::class);

    }

    public function getBlockPrefix(): string
    {
        return 'postal_code_edit_form';
    }
}