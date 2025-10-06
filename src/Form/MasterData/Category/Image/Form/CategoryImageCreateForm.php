<?php

namespace Silecust\WebShop\Form\MasterData\Category\Image\Form;

use Silecust\WebShop\Form\Common\File\FileCreateForm;
use Silecust\WebShop\Form\MasterData\Category\Image\DTO\CategoryImageDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryImageCreateForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder->add('categoryId', HiddenType::class);
        $builder->add('fileDTO', FileCreateForm::class);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {

            // removing save from the original form so that we don't have two save buttons
            $form = $event->getForm();
            $fileForm = $form->get("fileDTO");
            $fileForm->remove("save");
        });
        $builder->add('save', SubmitType::class,['label'=>'Save']);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => CategoryImageDTO::class]);
    }
    public function getBlockPrefix():string
    {
        return 'category_image_create_form';
    }
}