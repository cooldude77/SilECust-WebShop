<?php

namespace App\Form\MasterData\Customer\Address\Attribute\State;

use App\Form\MasterData\Customer\Address\Attribute\Country\CountryAutoCompleteField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class StateEditForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder->add('id', HiddenType::class);
        $builder->add('code', TextType::class);
        $builder->add('name', TextType::class);
        $builder->add('countryId', CountryAutoCompleteField::class);

    }

    public function getBlockPrefix(): string
    {
        return 'state_edit_form';
    }
}