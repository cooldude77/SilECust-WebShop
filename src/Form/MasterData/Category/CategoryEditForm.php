<?php

namespace App\Form\MasterData\Category;

use App\Form\CategoryAutoCompleteField;
use App\Form\MasterData\Category\DTO\CategoryDTO;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryEditForm extends AbstractType
{

    public function __construct(private readonly CategoryRepository $categoryRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('id', HiddenType::class);
        $builder->add('name', TextType::class);
        $builder->add('description', TextType::class);
        $builder->add('parentId', CategoryAutoCompleteField::class, ['required' => false, 'mapped' => false,'label'=>'Parent Category']);

        $builder->add('save', SubmitType::class);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $formEvent) {
            $data = $formEvent->getData();
            $formEvent->getForm()->add('parent', NumberType::class);
            $data['parent'] = $data['parentId'];
            $formEvent->setData($data);
        });

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $formEvent) {
            $data = $formEvent->getData();
            $form = $formEvent->getForm();

            $form->get('parentId')->setData($this->categoryRepository->findOneBy(['id' => $data->parent]));
        });

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => CategoryDTO::class]);
    }


    public function getBlockPrefix(): string
    {
        return 'category_edit_form';
    }
}