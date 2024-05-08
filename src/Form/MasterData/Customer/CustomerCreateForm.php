<?php

namespace App\Form\MasterData\Customer;

use App\Entity\Customer;
use App\Form\MasterData\Customer\DTO\CustomerDTO;
use App\Form\MasterData\Product\Attribute\DTO\ProductAttributeDTO;
use App\Repository\CustomerRepository;
use App\Repository\SalutationRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerCreateForm extends AbstractType
{
    public function __construct(private readonly SalutationRepository $salutationRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('salutationId',ChoiceType::class, [// validation message if the data
                                                         // transformer fails
                                                         'choices' => $this->fill()]);
        $builder->add('firstName', TextType::class);
        $builder->add('middleName', TextType::class);
        $builder->add('lastName', TextType::class);
        $builder->add('givenName', TextType::class);
        $builder->add('email',TextType::class);
        $builder->add('phoneNumber',TextType::class);

        $builder->add('save', SubmitType::class);

    }

    private function fill(): array
    {
        $selectArray = [];
        $salutations = $this->salutationRepository->findAll();
        foreach ($salutations as $bu) {

            $selectArray[$bu->getDescription()] = $bu->getId();
        }
        return $selectArray;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => CustomerDTO::class]);
    }

    public function getBlockPrefix(): string
    {
        return 'customer_create_form';
    }

}