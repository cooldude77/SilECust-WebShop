<?php

namespace Silecust\WebShop\Form\MasterData\Employee;

use Silecust\WebShop\Entity\Employee;
use Silecust\WebShop\Form\MasterData\Customer\DTO\SalutationAutoCompleteField;
use Silecust\WebShop\Form\MasterData\Employee\DTO\EmployeeDTO;
use Silecust\WebShop\Form\MasterData\Product\Attribute\DTO\ProductAttributeDTO;
use Silecust\WebShop\Repository\EmployeeRepository;
use Silecust\WebShop\Repository\SalutationRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeCreateForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
      //  $builder->add('salutation', SalutationAutoCompleteField::class, ['mapped' => false]);
        $builder->add('firstName', TextType::class);
        $builder->add('middleName', TextType::class,['required'=>false]);
        $builder->add('lastName', TextType::class);
        $builder->add('givenName', TextType::class,['required'=>false]);
        $builder->add('email',TextType::class);
        $builder->add('phoneNumber',TextType::class,['required'=>false]);

        $builder->add('save', SubmitType::class);

    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => EmployeeDTO::class]);
    }

    public function getBlockPrefix(): string
    {
        return 'employee_create_form';
    }

}