<?php

namespace Silecust\WebShop\Service\Testing\Fixtures;

use Silecust\WebShop\Entity\Category;
use Silecust\WebShop\Entity\Product;
use Silecust\WebShop\Factory\CategoryFactory;
use Silecust\WebShop\Factory\ProductFactory;
use Zenstruck\Foundry\Proxy;

trait ProductFixture
{
    public Category|Proxy $categoryA;

    public Product|Proxy $productA;

    public Category|Proxy $categoryB;

    public Product|Proxy $productB;
  public Product|Proxy $productInactive;

    public string $productAName = 'Prod name A';
    public string $productBName = 'Prod name B';

    public string|Proxy $productInactiveName = 'Inactive Product Name';

    public string $productADescription = 'Product description A';
    public string $productBDescription = 'Product description B';

    public string|Proxy $productInactiveDescription = 'Inactive Product Description';


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

        $this->productA = ProductFactory::createOne([
            'category' => $this->categoryA,
            'name' => $this->productAName,
            'description' => $this->productADescription,
            'isActive' => true]);

        $this->categoryB = CategoryFactory::createOne(

            ['name' => $this->categoryBName,
                'description' => $this->categoryBDescription]
        );

        $this->productB = ProductFactory::createOne(['category' => $this->categoryB,
            'name' => $this->productBName,
            'description' => $this->productBDescription,
            'isActive' => true]);     
        
        
        $this->productInactive = ProductFactory::createOne(['category' => $this->categoryA,
            'name' => $this->productInactiveName,
            'description' => $this->productInactiveDescription,
            'isActive' => false]);
    }

}