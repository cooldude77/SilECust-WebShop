<?php

namespace Silecust\WebShop\Form\Common\UI\Search;

use Silecust\WebShop\Form\CategoryAutoCompleteField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('searchTerm',SearchType::class);

    }

}