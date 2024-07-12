<?php

namespace App\Form\Common\Order\Header;

use App\Form\MasterData\Customer\DTO\CustomerAutoCompleteField;
use App\Form\MasterData\Customer\Transformer\CustomerToIdTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class OrderHeaderCreateForm extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('customer', CustomerAutoCompleteField::class);
        $builder->add('save', SubmitType::class);
    }
}