<?php

namespace Silecust\WebShop\Service\Testing\Fixtures;

use Silecust\WebShop\Entity\Category;
use Silecust\WebShop\Entity\Product;
use Silecust\WebShop\Factory\CategoryFactory;
use Silecust\WebShop\Factory\ProductFactory;
use Zenstruck\Foundry\Proxy;

trait ProductFixture
{
    public Category|Proxy $category1;

    public Product|Proxy $product1;

    public Category|Proxy $category2;

    public Product|Proxy $product2;
  public Product|Proxy $productInactive;

    public string $product1Name = 'Prod name 1';
    public string $product2Name = 'Prod name 2';

    public string|Proxy $productInactiveName = 'Inactive Product Name';

    public string $product1Description = 'Product description 1';
    public string $product2Description = 'Product description 2';

    public string|Proxy $productInactiveDescription = 'Inactive Product Description';


    public string $category1Name = 'Cat 1';
    public string $category2Name = 'Cat 2';

    public string $category1Description = 'Category 1';
    public string $category2Description = 'Category 2';


    function createProductFixtures(): void
    {
        $this->category1 = CategoryFactory::createOne(
            ['name' => $this->category1Name,
                'description' => $this->category1Description]
        );

        $this->product1 = ProductFactory::createOne([
            'category' => $this->category1,
            'name' => $this->product1Name,
            'description' => $this->product1Description,
            'active' => true]);

        $this->category2 = CategoryFactory::createOne(

            ['name' => $this->category2Name,
                'description' => $this->category2Description]
        );

        $this->product2 = ProductFactory::createOne(['category' => $this->category2,
            'name' => $this->product2Name,
            'description' => $this->product2Description,
            'active' => true]);
        
        
        $this->productInactive = ProductFactory::createOne(['category' => $this->category1,
            'name' => $this->productInactiveName,
            'description' => $this->productInactiveDescription,
            'active' => false]);
    }

}