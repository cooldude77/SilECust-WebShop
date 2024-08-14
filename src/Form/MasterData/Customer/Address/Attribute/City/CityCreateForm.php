<?php

namespace App\Form\MasterData\Customer\Address\Attribute\City;

use App\Form\MasterData\Customer\Address\Attribute\Country\CountryAutoCompleteField;
use App\Form\MasterData\Customer\Address\Attribute\State\StateAutoCompleteField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CityCreateForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options):void
    {

        $builder->add('code',TextType::class);
        $builder->add('name',TextType::class);
        $builder->add('state', StateAutoCompleteField::class,['mapped'=>false]);
        $builder->add('save', SubmitType::class);

    }

    public function getBlockPrefix():string
    {
        return 'city_create_form';
    }
}