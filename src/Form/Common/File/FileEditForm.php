<?php

namespace Silecust\WebShop\Form\Common\File;

use Silecust\WebShop\Form\Common\File\DTO\FileDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FileEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('id', HiddenType::class);

        $builder->add('name', TextType::class, ['attr' => ['readOnly' => 'read_only']]);

        $builder->add('yourFileName', TextType::class);

        $builder->add('uploadedFile', FileType::class, ['label' => 'File']);

        $builder->add('save', SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => FileDTO::class]);
    }
}