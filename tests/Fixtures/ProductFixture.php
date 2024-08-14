<?php

namespace App\Tests\Fixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Factory\CategoryFactory;
use App\Factory\ProductFactory;
use Zenstruck\Foundry\Proxy;

trait ProductFixture
{
    public Category|Proxy $categoryA;

    public Product|Proxy $productA;

    public Category|Proxy $categoryB;

    public Product|Proxy $productB;

    public string $productAName = 'Prod A';
    public string $productBName = 'Prod B';


    public string $productADescription = 'Product A';
    public string $productBDescription = 'Product B';


    public string $categoryAName = 'Cat A';
    public string $categoryBName = 'Cat B';

    public string $categoryADescription = 'Category A';
    public string $categoryBDescription = 'Category B';


    function createProductFixtures(): void
    {
        $this->categoryA = CategoryFactory::createOne(
            ['name' => $this->categoryAName,
             'description' => $this->categoryADescription]
        );

        $this->productA = ProductFactory::createOne(['category' => $this->categoryA,
                                                     'name' => $this->productAName,
                                                     'description' => $this->productADescription,
                                                     'isActive' => true]);

        $this->categoryB = CategoryFactory::createOne(

            ['name' => $this->categoryBName,
             'description' => $this->categoryBDescription]
        );

        $this->productB = ProductFactory::createOne(['category' => $this->categoryB,
                                                     'name' => 'Product B',
                                                     'description' => 'B new product',
                                                     'isActive' => true]);
    }

}