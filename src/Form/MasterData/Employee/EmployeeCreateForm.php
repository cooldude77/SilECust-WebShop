<?php

namespace App\Form\MasterData\Employee;

use App\Entity\Employee;
use App\Form\MasterData\Customer\DTO\SalutationAutoCompleteField;
use App\Form\MasterData\Employee\DTO\EmployeeDTO;
use App\Form\MasterData\Product\Attribute\DTO\ProductAttributeDTO;
use App\Repository\EmployeeRepository;
use App\Repository\SalutationRepository;
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