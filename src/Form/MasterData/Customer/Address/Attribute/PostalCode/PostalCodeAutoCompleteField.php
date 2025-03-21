<?php

namespace Silecust\WebShop\Form\MasterData\Customer\Address\Attribute\PostalCode;

use Silecust\WebShop\Entity\PostalCode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;

#[AsEntityAutocompleteField]
class PostalCodeAutoCompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => PostalCode::class,
            'placeholder' => 'Choose a Pin Code',
            'choice_label' => 'code',
            'choice_value' => 'id',
            // 'security' => 'ROLE_SOMETHING',
        ]);
    }

    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
    }
}
