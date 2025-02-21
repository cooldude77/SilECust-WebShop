<?php

namespace Silecust\WebShop\Tests\Controller\MasterData\Category;

use Silecust\WebShop\Entity\Category;
use Silecust\WebShop\Factory\CategoryFactory;
use Silecust\WebShop\Tests\Fixtures\EmployeeFixture;
use Silecust\WebShop\Tests\Utility\SelectElement;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Loader\Configurator\Traits\FactoryTrait;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use function PHPUnit\Framework\assertEquals;

/**
 *  Read boostrap.php comments for additional info
 */
class CategoryControllerTest extends WebTestCase
{

    use HasBrowser, selectElement, EmployeeFixture, Factories;

    protected function setUp(): void
    {
        $this->browser()->visit('/logout');
        $this->createEmployeeFixtures();
    }

    protected function tearDown(): void
    {
        $this->browser()->visit('/logout');

    }

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testCreateSingleWithoutAParent()
    {

        $uri = '/admin/category/create';
        $this->browser()
            ->visit($uri)
            // test: not authenticated
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());

            })
            ->post($uri,
                [
                    'body' => [
                        'category_create_form' => [
                            'name' => 'Cat1',
                            'description' => 'Category 1',
                            'parentId' => null
                        ],
                    ],
                ])
            ->assertSuccessful();

        $created = CategoryFactory::find(array('name' => "Cat1"));

        assertEquals('Cat1', $created->getName());
        assertEquals('Category 1', $created->getDescription());
        assertEquals("/{$created->getId()}", $created->getPath());

    }

    private string $token;

    public function testCreateWithAParent()
    {

        $uri = '/admin/category/create';

        $category = CategoryFactory::createOne(['name' => 'CatParent', 'description' => 'Category Parent']);

        // The value of category->getId() will be  1

        $this->browser()
            ->visit($uri)
            // test: not authenticated
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
                $session = $browser->client();
            })
            ->post($uri,
                [
                    'body' => [
                        'category_create_form' => [
                            'name' => 'CatChildWithParent',
                            'description' => 'Category Child With Parent',
                            'parentId' => $category->getId()
                        ],
                    ],
                ])
            ->assertSuccessful();


        $created = CategoryFactory::find(array('name' => "CatChildWithParent"));

        assertEquals('Category Child With Parent', $created->getDescription());
        assertEquals($category->getId(), $created->getParent()->getId());
        assertEquals("{$created->getParent()->getPath()}/{$created->getId()}", $created->getPath());


    }


    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testEditWithoutNullParent()
    {

        $categoryParent1 = CategoryFactory::createOne(['name' => 'CatParent1', 'description' => 'Category Parent1']);
        $categoryParent2 = CategoryFactory::createOne(['name' => 'CatParent2', 'description' => 'Category Parent2']);

        $category = CategoryFactory::createOne(['name' => 'Cat1', 'description' => 'Category 1', 'parent' => $categoryParent1]);

        $id = $category->getId();

        $uri = "/admin/category/$id/edit";

        $this->browser()
            ->visit($uri)
            // test: not authenticated
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->use(function (Browser $browser) {
                $response = $browser->client()->getResponse();
            })
            ->post($uri,
                [
                    'body' => [
                        'category_edit_form' => [
                            'id' => $category->getId(),
                            'name' => 'CatChanged',
                            'description' => 'Category Changed',
                            'parentId' => $categoryParent2->getId()
                        ],
                    ],
                ])
            ->assertSuccessful();


        $edited = CategoryFactory::find($category->getId());

        /** @var EntityManager $entityManager */
     //   $entityManager = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
       // $edited = $entityManager->getRepository(Category::class)->find($category->getId());
        assertEquals('CatChanged', $edited->getName());
        assertEquals('Category Changed', $edited->getDescription());
        assertEquals($categoryParent2->getId(), $edited->getParent()->getId());
        assertEquals("{$edited->getParent()->getPath()}/{$edited->getId()}", $edited->getPath());

    }

    public function testEditWithNullParent()
    {

        $categoryParent1 = CategoryFactory::createOne(['name' => 'CatParent1', 'description' => 'Category Parent1']);
        $categoryParent2 = CategoryFactory::createOne(['name' => 'CatParent2', 'description' => 'Category Parent2']);

        $category = CategoryFactory::createOne(['name' => 'Cat1', 'description' => 'Category 1']);

        $id = $category->getId();

        $uri = "/admin/category/$id/edit";

        $this->browser()
            ->visit($uri)
            // test: not authenticated
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->use(function (Browser $browser) {
                $response = $browser->client()->getResponse();
            })
            ->post($uri,
                [
                    'body' => [
                        'category_edit_form' => [
                            'id' => $category->getId(),
                            'name' => 'CatChanged',
                            'description' => 'Category Changed',
                            'parentId' => $categoryParent2->getId()
                        ],
                    ],
                ])
            ->assertSuccessful();


        $edited = CategoryFactory::find($category->getId());

        assertEquals('CatChanged', $edited->getName());
        assertEquals('Category Changed', $edited->getDescription());
        assertEquals($categoryParent2->getId(), $edited->getParent()->getId());
        assertEquals("{$edited->getParent()->getPath()}/{$edited->getId()}", $edited->getPath());

    }

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testDisplay()
    {

        $category = CategoryFactory::createOne(['name' => 'Cat1', 'description' => 'Category 1']);
        $id = $category->getId();

        $uri = "/admin/category/$id/display";
        $this->browser()
            ->visit($uri)
            // test: not authenticated
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->assertSuccessful();

    }


    public function testList()
    {

        $uri = '/admin/category/list';
        $this->browser()->visit($uri)
            // test: not authenticated
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)->assertSuccessful();

    }


}
