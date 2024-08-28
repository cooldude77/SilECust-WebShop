<?php

namespace App\Form\MasterData\Customer\Address\Attribute\City;

use App\Entity\City;
use App\Form\MasterData\Customer\Address\Attribute\City\DTO\CityDTO;
use App\Form\MasterData\Customer\Address\Attribute\Country\DTO\CountryDTO;
use App\Form\MasterData\Customer\Address\Attribute\State\DTO\StateDTO;
use App\Form\MasterData\Customer\Address\Attribute\State\StateAutoCompleteField;
use App\Repository\StateRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CityEditForm extends AbstractType
{
    public function __construct( private readonly StateRepository $stateRepository)
    {
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder->add('id', HiddenType::class);
        $builder->add('code',TextType::class);
        $builder->add('name',TextType::class);
        $builder->add('state', StateAutoCompleteField::class,['mapped'=>false]);
        $builder->add('save', SubmitType::class);
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $formEvent) {

            $formEvent->getForm()->add('stateId', HiddenType::class);

            // dto jugglery
            $data = $formEvent->getData();

            $data['stateId'] = $data['state'];

            $formEvent->setData($data);
        });
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $formEvent) {
            /** @var CityDTO $data */
            $data = $formEvent->getData();
            $form = $formEvent->getForm();

            $form->get('state')->setData($this->stateRepository->findOneBy(['id' => $data->stateId]));
        });

    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class', [CityDTO::class]]);
    }
    public function getBlockPrefix(): string
    {
        return 'city_edit_form';
    }
}