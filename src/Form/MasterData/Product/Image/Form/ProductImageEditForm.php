<?php

namespace App\Form\MasterData\Product\Image\Form;

use App\Form\Common\File\FileEditForm;
use App\Form\MasterData\Product\Image\DTO\ProductImageDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductImageEditForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('fileDTO', FileEditForm::class);

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
        $resolver->setDefaults(['data_class' => ProductImageDTO::class]);
    }
}