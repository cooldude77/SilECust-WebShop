<?php

namespace Silecust\WebShop\Form\Admin\User;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('emailId', TextType::class);
        $builder->add('password', PasswordType::class);
        $builder->add('type', EntityType::class, [
                'class' => 'Silecust\WebShop\Entity\UserType',
                'choice_label' => 'description'
            ]
        );

        $builder->add('save', SubmitType::class);

    }
}