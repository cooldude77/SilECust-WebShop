<?php

namespace App\Form\MasterData\Customer\Address\Attribute\State;

use App\Form\MasterData\Customer\Address\Attribute\Country\CountryAutoCompleteField;
use App\Form\MasterData\Customer\Address\Attribute\State\DTO\StateDTO;
use App\Form\MasterData\Price\DTO\PriceProductBaseDTO;
use App\Repository\CountryRepository;
use App\Repository\ProductRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StateEditForm extends AbstractType
{
    public function __construct( private readonly CountryRepository $countryRepository)
    {
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder->add('id', HiddenType::class);
        $builder->add('code', TextType::class);
        $builder->add('name', TextType::class);
        $builder->add('country', CountryAutoCompleteField::class,['mapped'=>false]);
        $builder->add('save', SubmitType::class);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $formEvent) {

            $formEvent->getForm()->add('countryId', HiddenType::class);

            // dto jugglery
            $data = $formEvent->getData();

            $data['countryId'] = $data['country'];

            $formEvent->setData($data);
        });
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $formEvent) {
            /** @var StateDTO $data */
            $data = $formEvent->getData();
            $form = $formEvent->getForm();

            $form->get('country')->setData($this->countryRepository->findOneBy(['id' => $data->countryId]));
        });
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class', [StateDTO::class]]);
    }
    public function getBlockPrefix(): string
    {
        return 'state_edit_form';
    }
}