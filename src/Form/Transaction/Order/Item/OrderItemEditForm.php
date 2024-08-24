<?php

namespace App\Form\Transaction\Order\Item;

use App\Form\MasterData\Product\ProductAutoCompleteField;
use App\Form\Transaction\Order\Item\DTO\OrderItemDTO;
use App\Repository\ProductRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderItemEditForm extends AbstractType
{

    public function __construct(private readonly ProductRepository $productRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('id', HiddenType::class);
        $builder->add('product', ProductAutoCompleteField::class,['mapped'=>false]);
        $builder->add('quantity', NumberType::class);
        $builder->add('save', SubmitType::class);


        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $formEvent) {
            $data = $formEvent->getData();
            $formEvent->getForm()->add('productId', NumberType::class);
            $data['productId'] = $data['product'];
            $formEvent->setData($data);
        });
        
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $formEvent) {
            $data = $formEvent->getData();
            $form = $formEvent->getForm();

            $form->get('product')->setData($this->productRepository->findOneBy(['id' => $data->productId]));
        });


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => OrderItemDTO::class]);
    }

    public function getBlockPrefix(): string
    {

        return 'order_item_edit_form';
    }

}