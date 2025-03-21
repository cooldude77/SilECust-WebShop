<?php

namespace Silecust\WebShop\Form\MasterData\Customer\Address\Attribute\PostalCode;

use Silecust\WebShop\Entity\PostalCode;
use Silecust\WebShop\Form\MasterData\Customer\Address\Attribute\City\CityAutoCompleteField;
use Silecust\WebShop\Form\MasterData\Customer\Address\Attribute\City\DTO\CityDTO;
use Silecust\WebShop\Form\MasterData\Customer\Address\Attribute\PostalCode\DTO\PostalCodeDTO;
use Silecust\WebShop\Repository\CityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostalCodeCreateForm extends AbstractType
{
    public function __construct( private readonly CityRepository $cityRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options):void
    {

        $builder->add('code',TextType::class);
        $builder->add('name',TextType::class);
        $builder->add('city', CityAutoCompleteField::class,['mapped'=>false]);

        $builder->add('save', SubmitType::class);
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $formEvent) {

            $formEvent->getForm()->add('cityId', HiddenType::class);

            // dto jugglery
            $data = $formEvent->getData();

            $data['cityId'] = $data['city'];

            $formEvent->setData($data);

        });
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $formEvent) {
            /** @var PostalCodeDTO $data */
            $data = $formEvent->getData();
            $form = $formEvent->getForm();

            $form->get('city')->setData($this->cityRepository->findOneBy(['id' => $data->cityId]));
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class', [PostalCodeDTO::class]]);
    }

    public function getBlockPrefix():string
    {
        return 'postal_code_create_form';
    }
}