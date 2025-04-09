<?php

namespace Silecust\WebShop\Form\MasterData\Customer\Image\Form;

use Silecust\WebShop\Form\Common\File\FileCreateForm;
use Silecust\WebShop\Form\MasterData\Customer\Image\DTO\CustomerImageDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerImageCreateForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder->add('customerId', HiddenType::class);
        $builder->add('fileDTO', FileCreateForm::class);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {

            // removing save from the original form so that we don't have two save buttons
            $form = $event->getForm();
            $fileForm = $form->get("fileDTO");
            $fileForm->remove("save");
        });
        $builder->add('save', SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => CustomerImageDTO::class]);
    }
    public function getBlockPrefix():string
    {
        return 'customer_image_create_form';
    }
}