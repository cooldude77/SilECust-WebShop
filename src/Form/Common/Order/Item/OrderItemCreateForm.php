<?php

namespace App\Form\Common\Order\Item;

use App\Form\Common\Order\Header\Transformer\OrderHeaderToIdTransformer;
use App\Form\MasterData\Product\ProductAutoCompleteField;
use App\Form\MasterData\Product\Transformer\ProductToIdTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class OrderItemCreateForm extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('product', ProductAutoCompleteField::class);
        $builder->add('quantity', NumberType::class);

        $builder->add('save', SubmitType::class);

    }
}