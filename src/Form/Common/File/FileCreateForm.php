<?php

namespace Silecust\WebShop\Form\Common\File;

use Silecust\WebShop\Form\Common\File\DTO\FileDTO;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FileCreateForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class,
            ['help' => 'System generated file name', 'attr' => ['readOnly' => 'read_only']]);

        $builder->add('yourFileName', TextType::class);


        $builder->add('uploadedFile', FileType::class, ['label' => 'File', 'required' => false]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {

            $fileFormDTO= new FileDTO();

            $fileFormDTO->name = uniqid(rand(), true);

            $event->setData($fileFormDTO);
        });


        $builder->add('save', SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => FileDTO::class]);
    }
}