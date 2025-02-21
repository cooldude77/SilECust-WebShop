<?php

namespace Silecust\WebShop\Form\Admin\User\Transformer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('description', TextType::class);

        $builder->get('description')
            ->addModelTransformer(new CallbackTransformer(
                function ($descriptionsAsArray): string {
                    // transform the array to a string
                    return implode(', ', $descriptionsAsArray);
                },
                function ($descriptionAsString): array {
                    // transform the string back to an array
                    return explode(', ', $descriptionAsString);
                }
            ));
    }
}